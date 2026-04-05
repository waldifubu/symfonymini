import "./dropUpload.css"
import React, {ChangeEvent, DragEvent, useEffect, useRef, useState} from 'react';
import {Button, ButtonGroup, Input, NavLink} from "reactstrap";
import axios from "axios";
import {Link} from "react-router-dom";

export interface FileMetadata {
    url: string;
    name: string;
    type: string;
    size: number;
    uuid: string;
    storage?: string;
    duration?: number;
}

interface UploadResponse {
    name: string;
    uuid: string;
    type: string;
    size: number;
    url: string;
    duration?: number;
    message?: string;
}

// Props interface for the component
interface DropUploadProps {
    onFilesUploaded?: (metadata: FileMetadata[]) => void; // Callback to update parent
    fileType: string;
    bucket?: string;
}

interface SourceOption {
    value: string;
    label: string;
}

const DropUpload: React.FC<DropUploadProps> = ({onFilesUploaded, fileType, bucket}: DropUploadProps) => {
    // State management
    const [files, setFiles] = useState<FileList | null>(null);
    const lastValidFilesRef = useRef<FileList | null>(null);
    const [path, setPath] = useState<string>(bucket || '');
    const [statusMessage, setStatusMessage] = useState<string>("🤷‍ Nothing's uploaded");
    const [progress, setProgress] = useState<number>(0);
    const [fileMetadata, setFileMetadata] = useState<FileMetadata[]>([]);
    const [isDragOver, setIsDragOver] = useState<boolean>(false);
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);
    const [selectedSource, setSelectedSource] = useState<string>('h3');
    const [filename, setFilename] = useState<string>('');

    const sources = [
        {value: 'h3', label: 'Local'},
        {value: 'terabox', label: 'Terabox'},
        {value: 'filenio', label: 'Filen.io'},
        {value: 'google-cloud', label: 'Google Cloud'}
    ];

    // Refs for direct DOM access if needed
    const fileInputRef = useRef<HTMLInputElement>(null);
    const dropAreaRef = useRef<HTMLDivElement>(null);
    const formRef = useRef<HTMLFormElement>(null);

    // Configuration
    const ALLOWED_TYPES = ["image/webp", "image/jpeg", "image/png", "audio/mpeg"];
    const SIZE_LIMIT = 1024 * 1024 * 1024 * 9999999024 * 99999999999 * 9999999999;
    const UPLOAD_URL = "/api/file_upload";

    // Handle form submission
    const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        setFileMetadata([]);

        if (!files || files.length === 0) {
            updateStatusMessage("❌ Please select files to upload");
            return;
        }

        try {
            showPendingState();
            await uploadFiles(files);

        } catch (err: any) {
            updateStatusMessage(`❌ ${err.message}`);
        }
    };

    // Handle file input change
    const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files.length > 0) {
            const fileList = e.target.files;
            setFiles(fileList);
            lastValidFilesRef.current = fileList;

            setFilename(fileList[0].name);

            // Create preview for first file if it's an image
            if (fileList[0] && fileList[0].type.startsWith('image/')) {
                const url = URL.createObjectURL(fileList[0]);
                setPreviewUrl(url);
            } else {
                // Clear preview if not an image
                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                }
                setPreviewUrl(null);
            }
        } else {
            if (null != lastValidFilesRef.current && lastValidFilesRef.current.length > 0) {
                console.log('Dialog canceled - keeping previous selection');
            } else {
                resetFormState();
            }
        }
    };

    // Handle drop event
    const handleDrop = (event: DragEvent<HTMLDivElement>) => {
        event.preventDefault();
        setIsDragOver(false);

        const fileList = event.dataTransfer.files;

        if (!fileList || fileList.length === 0) return;

        resetFormState();

        try {
            setFiles(fileList);
            lastValidFilesRef.current = fileList;
            updateStatusMessage(`📄 ${fileList.length} file(s) selected from drop`);

            // Create preview for first file if it's an image
            if (fileList[0] && fileList[0].type.startsWith('image/')) {
                const url = URL.createObjectURL(fileList[0]);
                setPreviewUrl(url);
                setFilename(fileList[0].name);
            } else {
                setPreviewUrl(null);
            }

            // Trigger button animation
            const button = event.currentTarget.querySelector('button');
            if (button) {
                button.classList.add('grow');
                setTimeout(() => {
                    button.classList.remove('grow');
                }, 1000);
            }
        } catch (err: any) {
            updateStatusMessage(err.message);
        }
    };

    // Clean up object URLs when component unmounts or preview changes
    useEffect(() => {
        return () => {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }
        };
    }, [previewUrl]);

    // Upload files to server
    const uploadFiles = async (fileList: FileList) => {
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener("progress", (event) => {
            if (event.lengthComputable) {
                const progressValue = event.loaded / event.total;
                updateStatusMessage(`⏳ Uploaded locally ${event.loaded} bytes of ${event.total}`);
                updateProgressBar(progressValue);
            }
        });

        xhr.addEventListener("loadend", () => {
            setFileMetadata([]);
            if (xhr.status === 201 || xhr.status === 200) {
                const response: UploadResponse = JSON.parse(xhr.responseText);
                updateStatusMessage("✅ Success" + (xhr.status === 200 ? " (already existed)" : " (OK)"));
                renderFilesMetadata(fileList, response);
            } else {
                try {
                    const response = JSON.parse(xhr.responseText);
                    updateStatusMessage(`❌ Error: ${response.message}`);
                } catch {
                    updateStatusMessage(`❌ Error: ${xhr.statusText}`);
                }
            }

            setIsSubmitting(false);
            updateProgressBar(0);
        });

        xhr.addEventListener("error", () => {
            updateStatusMessage("❌ Network error occurred");
            setIsSubmitting(false);
            updateProgressBar(0);
        });

        const formData = new FormData();
        /*
        for (let i = 0; i < fileList.length; i++) {
            formData.append("file", fileList[i]);
        }
        */
        formData.append("file", fileList[0]);
        formData.append("path", path);
        formData.append("storage", selectedSource);
        formData.append("discr", fileType);
        formData.append("filename", filename);

        xhr.open("POST", UPLOAD_URL);
        xhr.send(formData);
    };

    // Update status message
    const updateStatusMessage = (text: string) => {
        setStatusMessage(text);
    };

    // Update progress bar
    const updateProgressBar = (value: number) => {
        setProgress(value);
    };

    // Show pending state
    const showPendingState = () => {
        setIsSubmitting(true);
        updateStatusMessage("⏳ Pending...");
    };

    // Reset form state
    const resetFormState = () => {
        setFileMetadata([]);
        // Clear preview URL when resetting
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            setPreviewUrl(null);
        }
        setIsSubmitting(false);
        updateStatusMessage("🤷‍♂ Nothing's uploaded");
        updateProgressBar(0);
    };

    // Render files metadata
    const renderFilesMetadata = (fileList: FileList, response: UploadResponse) => {
        const metadata: FileMetadata[] = [];
        for (let i = 0; i < fileList.length; i++) {
            // const file = fileList[i];
            metadata.push({
                name: response.name,
                type: response.type,
                size: response.size,
                uuid: response.uuid,
                url: response.url,
                duration: response?.duration,
                // storage: response.storage
            });
        }
        setFileMetadata(metadata);
        if (onFilesUploaded) {
            onFilesUploaded(metadata);
        }
    };

    // Drag and drop event handlers
    const handleDragEnter = (event: DragEvent<HTMLDivElement>) => {
        event.preventDefault();
        setIsDragOver(true);
    };

    const handleDragOver = (event: DragEvent<HTMLDivElement>) => {
        event.preventDefault();
        setIsDragOver(true);
    };

    const handleDragLeave = (event: DragEvent<HTMLDivElement>) => {
        event.preventDefault();
        if (!event.currentTarget.contains(event.relatedTarget as Node)) {
            setIsDragOver(false);
        }
    };

    // Calculate progress percentage
    const progressPercent = (progress * 100).toFixed(2);

    // Function to clear the preview
    const clearPreview = () => {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            setPreviewUrl(null);
        }
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
        setFiles(null);
        lastValidFilesRef.current = null;
    };

    return (
        <div>
            <form ref={formRef} onSubmit={handleSubmit}>
                <div
                    ref={dropAreaRef}
                    id="dropArea"
                    className={`drop-area ${isDragOver ? 'highlight' : ''}`}
                    onDragEnter={handleDragEnter}
                    onDragOver={handleDragOver}
                    onDragLeave={handleDragLeave}
                    onDrop={handleDrop}
                >
                    <div className="d-inline-flex">
                        <label className="form-label">Storage:</label>
                        <ButtonGroup className="w-100">
                            {sources.map((source) => (
                                <Button
                                    key={source.value}
                                    color={selectedSource === source.value ? 'primary' : 'outline-primary'}
                                    active={selectedSource === source.value}
                                    onClick={() => setSelectedSource(source.value)}
                                    className="flex-grow-1"
                                >
                                    {source.label}
                                </Button>
                            ))}
                        </ButtonGroup>
                    </div>
                    <div className="mb-2">
                        <small className="text-muted">
                            Selected: {sources.find(opt => opt.value === selectedSource)?.label}
                        </small>
                    </div>

                    <p>Drag and drop files here or</p>
                    <Input
                        type="file"
                        innerRef={fileInputRef}
                        onChange={handleFileChange}
                        style={{margin: '1px auto', padding: '10px 10px', width: '90%'}}
                    />

                    {/* Image Preview Section */}
                    {previewUrl && (
                        <div style={{margin: '20px 0', textAlign: 'center'}}>
                            <h5>Image Preview:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
                            <div style={{position: 'relative', display: 'inline-block'}}>
                                <img
                                    src={previewUrl}
                                    alt="Preview"
                                    style={{
                                        maxWidth: '300px',
                                        maxHeight: '300px',
                                        objectFit: 'contain'
                                    }}
                                    //onLoad={() => console.log('Image loaded successfully')}
                                    onError={() => {
                                        console.error('Failed to load image preview');
                                        setPreviewUrl(null);
                                    }}
                                />
                                <button
                                    type="button"
                                    onClick={clearPreview}
                                    style={{
                                        position: 'absolute',
                                        top: '-55px',
                                        right: '0px',
                                        background: 'var(--bs-danger)',
                                        color: 'white',
                                        border: 'none',
                                        borderRadius: '50%',
                                        width: '14px',
                                        height: '30px',
                                        cursor: 'pointer'
                                    }}
                                >
                                    <span style={{position: "absolute", right: '5px', bottom: '-6px'}} className="fs-2">
                                        &times;
                                    </span>
                                </button>
                            </div>
                            <p style={{fontSize: '12px', color: '#666', marginTop: '5px'}}>
                                Click the X to remove the preview
                            </p>
                        </div>
                    )}

                    <div style={{margin: '20px auto'}} className={"d-flex"}>
                        <label className={"mt-2 me-2 w-25"} htmlFor="pathInput">Upload Path:</label>
                        <Input
                            className={"w-50"}
                            name="duration"
                            type="text"
                            id="pathInput"
                            value={path}
                            onChange={(e) => setPath(e.target.value)}
                            placeholder="Enter upload path"
                            required={true}
                        />
                    </div>

                    <div style={{margin: '20px auto'}} className={"d-flex"}>
                        <label className={"mt-2 me-2 w-25"} htmlFor="pathInput">Filename:</label>
                        <Input
                            className={"w-50"}
                            name="duration"
                            type="text"
                            id="pathInput"
                            value={filename}
                            onChange={(e) => setFilename(e.target.value)}
                            required={true}
                        />
                    </div>
                </div>

                <button type="submit" disabled={isSubmitting || !files || files.length === 0}>
                    {isSubmitting ? (
                        <>
                            Uploading...
                            <span className="spinner-border text-primary ms-2 spinner-border-sm" role="status">
                                  <span className="visually-hidden">Uploading...</span>
                            </span>
                        </>
                    ) : (
                        'Upload File'
                    )}
                </button>
            </form>

            <div id="statusMessage" style={{margin: '20px 0'}}>
                {statusMessage}
            </div>

            <div style={{margin: '20px 0'}}>
                <div className="progress" role="progressbar">
                    <div id="progri" className="progress-bar bg-success" style={{
                        height: '16px',
                        width: `${progressPercent}%`,
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center'
                    }}>
                        {progressPercent}%
                    </div>
                </div>
            </div>

            <div style={{margin: '20px 0'}}>
                <h3>File Metadata:</h3>
                <ul className="meta-list" style={{listStyle: 'none', padding: 0, paddingLeft: '20px'}}>
                    {fileMetadata.map((file: FileMetadata, index: number) => (
                        <li key={index} style={{borderBottom: '1px solid #ccc', padding: '10px 0'}}>
                            <p><strong>Name:</strong> {file.name}</p>
                            <p><strong>Type:</strong> {file.type}</p>
                            <p><strong>Size:</strong> {file.size} bytes</p>
                            <p><strong>UUID:</strong> {file.uuid}</p>
                            <p><strong>URL:</strong> <Link target={"_blank"} to={file.url}>Open</Link></p>
                            {!!file.duration && (<p><strong>Duration:</strong> {file.duration} sec.</p>)}
                        </li>
                    ))}
                </ul>
            </div>
        </div>
    );
};

export default DropUpload;
