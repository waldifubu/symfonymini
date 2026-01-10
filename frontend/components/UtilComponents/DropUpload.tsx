import "./dropUpload.css"
import React, {ChangeEvent, DragEvent, FormEvent, useRef, useState} from 'react';

interface FileMetadata {
    name: string;
    type: string;
    size: number;
    uuid: string;
}

interface UploadResponse {
    name: string;
    uuid: string;
    type: string;
    size: number;
    message?: string;
}

const DropUpload: React.FC = () => {
    // State management
    const [files, setFiles] = useState<FileList | null>(null);
    const lastValidFilesRef = useRef<FileList | null>(null); // Store last valid selection
    const [path, setPath] = useState<string>('');
    const [statusMessage, setStatusMessage] = useState<string>("🤷‍♂ Nothing's uploaded");
    const [progress, setProgress] = useState<number>(0);
    const [fileMetadata, setFileMetadata] = useState<FileMetadata[]>([]);
    const [isDragOver, setIsDragOver] = useState<boolean>(false);
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);

    // Refs for direct DOM access if needed
    const fileInputRef = useRef<HTMLInputElement>(null);
    const dropAreaRef = useRef<HTMLDivElement>(null);
    const formRef = useRef<HTMLFormElement>(null);

    // Configuration
    const ALLOWED_TYPES = ["image/webp", "image/jpeg", "image/png", "audio/mpeg"];
    const SIZE_LIMIT = 1024 * 1024 * 1024 * 9999999024 * 99999999999; // 1 megabyte
    const UPLOAD_URL = "/api/file_upload";

    // Handle form submission
    const handleSubmit = async (event: FormEvent) => {
        event.preventDefault();

        if (!files || files.length === 0) {
            updateStatusMessage("❌ Please select files to upload");
            return;
        }

        try {
            // assertFilesValid(files);
            showPendingState();
            await uploadFiles(files);
        } catch (err: any) {
            updateStatusMessage(`❌ ${err.message}`);
        }
    };

    // Handle file input change
    const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
        // Only update state if user actually selected files
        if (e.target.files && e.target.files.length > 0) {
            // Convert FileList to array and update state
            setFiles(e.target.files);
            lastValidFilesRef.current = e.target.files;
        } else {
            // If no files but we had previous selection, keep it
            if (null!= lastValidFilesRef.current && lastValidFilesRef.current.length > 0) {
                console.log('Dialog canceled - keeping previous selection');
                lastValidFilesRef.current = e.target.files;
                // Files state already contains last valid selection
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
            // assertFilesValid(fileList);
            setFiles(fileList);
            updateStatusMessage(`📄 ${fileList.length} file(s) selected from drop`);

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

    // Upload files to server
    const uploadFiles = async (fileList: FileList) => {
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener("progress", (event) => {
            if (event.lengthComputable) {
                const progressValue = event.loaded / event.total;
                updateStatusMessage(`⏳ Uploaded ${event.loaded} bytes of ${event.total}`);
                updateProgressBar(progressValue);
            }
        });

        xhr.addEventListener("loadend", () => {
            if (xhr.status === 201) {
                const response: UploadResponse = JSON.parse(xhr.responseText);
                updateStatusMessage("✅ Success");
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
        for (let i = 0; i < fileList.length; i++) {
            formData.append("file", fileList[i]);
        }
        formData.append("path", path);

        xhr.open("POST", UPLOAD_URL);
        xhr.send(formData);
    };

    // Validate files
    const assertFilesValid = (fileList: FileList) => {
        return;
        for (let i = 0; i < fileList.length; i++) {
            const file = fileList[i];

            // Skip type validation as per your condition
            if (false && !ALLOWED_TYPES.includes(file.type)) {
                throw new Error(
                    `❌ File "${file.name}" could not be uploaded. Only images with the following types are allowed: WEBP, JPEG, PNG.`
                );
            }

            if (file.size > SIZE_LIMIT) {
                throw new Error(
                    `❌ File "${file.name}" could not be uploaded. Only images up to 1 MB are allowed.`
                );
            }
        }
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
        // Do not reset the file input value or files state here
        setIsSubmitting(false);
        updateStatusMessage("🤷‍♂ Nothing's uploaded");
        updateProgressBar(0);
    };

    // Render files metadata
    const renderFilesMetadata = (fileList: FileList, response: UploadResponse) => {
        const metadata: FileMetadata[] = [];
        for (let i = 0; i < fileList.length; i++) {
            const file = fileList[i];
            metadata.push({
                name: response.name,
                type: response.type,
                size: response.size,
                uuid: response.uuid
            });
        }
        setFileMetadata(metadata);
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

    // @ts-ignore
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
                    <p>Drag and drop files here or</p>
                    <input
                        type="file"
                        ref={fileInputRef}
                        onChange={handleFileChange}
                        style={{display: 'block', margin: '10px auto'}}
                    />

                    <div style={{margin: '20px 0'}}>
                        <label htmlFor="pathInput">Upload Path:</label>
                        <input
                            type="text"
                            id="pathInput"
                            value={path}
                            onChange={(e) => setPath(e.target.value)}
                            placeholder="Enter upload path"
                        />
                    </div>
                </div>


                <button
                    type="submit"
                    // disabled={isSubmitting || !files || files.length === 0}
                >
                    {isSubmitting ? 'Uploading...' : 'Upload Files'}
                </button>
            </form>

            <div id="statusMessage" style={{margin: '20px 0'}}>
                {statusMessage}
            </div>

            <div style={{margin: '20px 0'}}>
                <div className="progress" role="progressbar">
                    <div id="progri" className="progress-bar bg-success" style={{
                        height: '17px',
                        width: `${progressPercent}%`,
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center'
                    }}
                    >{progressPercent}%
                    </div>
                </div>


                {/*
                <div
                    id="progri"
                    style={{
                        width: `${progressPercent}%`,
                        height: '20px',
                        backgroundColor: '#4CAF50',
                        transition: 'width 0.3s',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        color: 'white',
                        fontWeight: 'bold'
                    }}
                >

                    {progressPercent}%
                </div>
                */}
            </div>

            <div style={{margin: '20px 0'}}>
                {/* ({fileMetadata.length} files) */}
                <h3>File Metadata:</h3>
                <ul id="fileListMetadata" style={{listStyle: 'none', padding: 0}}>
                    {fileMetadata.map((file, index) => (
                        <li key={index} style={{borderBottom: '1px solid #ccc', padding: '10px 0'}}>
                            <p><strong>Name:</strong> {file.name}</p>
                            <p><strong>Type:</strong> {file.type}</p>
                            <p><strong>Size:</strong> {file.size} bytes</p>
                            <p><strong>UUID:</strong> {file.uuid}</p>
                        </li>
                    ))}
                </ul>
            </div>
        </div>
    );
};

export default DropUpload;
