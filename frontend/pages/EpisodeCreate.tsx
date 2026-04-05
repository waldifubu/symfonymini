import React, {ChangeEvent, FC, Suspense, use, useActionState, useRef, useState} from "react";
import {
    Alert,
    Button,
    Card,
    CardBody,
    CardHeader,
    Col,
    Collapse,
    Container,
    Form,
    FormGroup,
    Input,
    Label,
    Row,
    Spinner
} from "reactstrap";
import MyButtonGroup from "../components/UtilComponents/MyButtonGroup";
import InputTag from "../components/UtilComponents/InputTag";
import {Tag} from "react-tag-input";
import DropUpload, {FileMetadata} from "../components/UtilComponents/DropUpload";
import {useParams} from 'react-router-dom';
import {handleSubmit, EpisodeActionState} from "../actions/EpisodeCreateAction";
import {ErrorBoundary} from "react-error-boundary";
import {Series} from "../types/Series";

const EpisodeCreate: React.FC = () => {
    const [typeSelected, setTypeSelected] = useState('');
    const [isTypeOpen, setTypeIsOpen] = useState(false);
    const [explicitSelected, setExplicitSelected] = useState(false);
    const [tags, setTags] = React.useState<Tag[]>([]);
    const [coverUrl, setCoverUrl] = React.useState<string>("");
    const [fileUrl, setFileUrl] = React.useState<string>("");
    const [duration, setDuration] = React.useState<string>("");
    const [filesize, setFilesize] = React.useState<string>("");
    const [series, setSeries] = React.useState<any>(null);
    const {id} = useParams(); // Get id from route params
    const formRef = useRef(null);
    const [bucket, setBucket] = React.useState<string>("");
    const [coverUuid, setCoverUuid] = React.useState<string>("");
    const [radioplayUuid, setRadioplayUuid] = React.useState<string>("");

    const showInfo = (value: string) => {
        setTypeIsOpen(value !== '');

        if (value == 'full') {
            setTypeSelected('<strong>Full (Default):</strong> Choose <em>Full</em> when publishing regular content for your show. Full Episodes will have an episode number and are the most common type of episodes.');
        } else if (value == 'trailer') {
            setTypeSelected('<strong>Trailer (Teaser):</strong> Choose <em>Trailer</em> when publishing a short, promotional piece of content that represents a preview of your show. Trailer episodes will be listed at the beginning of your episodes and will not have an episode number. Publishing a trailer episode is a great way to generate buzz for a new show and a good episode type to publish when launching a new show.');
        } else if (value == 'bonus') {
            setTypeSelected('<strong>Bonus (Extra Content):</strong> Choose <em>Bonus</em> when publishing extra content for your show (e.g., behind the scenes content, corrections, shout-outs, etc). Bonus episodes will not have an episode number and will be presented in order of publishing date. ');
        }
    }

    const handleCoverChange = (metadata: FileMetadata[]): void => {
        if (metadata.length > 0) {
            const coverData: FileMetadata = metadata[0];
            setCoverUrl(coverData.url);
            setCoverUuid(coverData.uuid);
        }
    };

    const handleRadioplayChange = (metadata: FileMetadata[]): void => {
        if (metadata.length > 0) {
            const radioplayData: FileMetadata = metadata[0];
            if (radioplayData.duration !== undefined) {
                setDuration(radioplayData?.duration?.toString());
            }
            setFilesize(radioplayData.size.toString());
            setFileUrl(radioplayData.url);
            setRadioplayUuid(radioplayData.uuid);
        }
    };

    const ShowSeriesInfo: React.FC<{ seriesPromise: Promise<Series> }> = ({seriesPromise}) => {
        const series: Series = use(seriesPromise);
        return <h3 className="text-muted">{series.title} ({series.count} episodes)</h3>;
    }

    const ShowBucket = ({seriesPromise}: { seriesPromise: Promise<Series> }) => {
        const series: Series = use(seriesPromise);
        setBucket(series.bucket);
        //return series.bucket;
    }

    const [state, formAction, isPending] = useActionState<EpisodeActionState | null, FormData>(handleSubmit.bind(null, id), null);

    const loadSeriesData = (): Promise<Series> | undefined => {
        if (!id) return undefined;
        document.body.classList.add('body-loading');
        return fetch(`/api/series/${id}/`)
            .then(response => response.json() as Promise<Series>)
            .then(data => {
                setBucket(data.bucket);
                return data;
            })
            .catch(error => {
                console.error('Error fetching series:', error);
                throw error;
            }).finally(() => {
                document.body.classList.remove('body-loading');
            });
    }

    const seriesData = loadSeriesData();

    return (
        <Container>
            <title>New Episode</title>
            <meta name="description"
                  content="Create a new episode for your series. Fill out the form with the episode details, upload your audio file and cover image, and publish your episode to share it with your audience."/>
            <Card>
                <CardHeader>
                    <div className="d-flex justify-content-between align-items-end">
                        <h2>New Episode</h2>
                        <ErrorBoundary fallback={<div>Error loading series info</div>}>
                            <Suspense fallback={<div><Spinner size={"md"} color={"primary"}></Spinner> Loading series info...</div>}>
                                {seriesData && <ShowSeriesInfo seriesPromise={seriesData}/>}
                            </Suspense>
                        </ErrorBoundary>
                    </div>
                </CardHeader>
                <CardBody>
                    {/*onSubmit={handleSubmit4}*/}
                    <Form action={formAction} autoComplete="on" ref={formRef}>
                        <div className={"d-inline-flex flex-wrap w-50"}>
                            <FormGroup className={"flex-fill me-2"}>
                                <Label for="no">
                                    <span id="no">Episode No. (Number)</span>
                                </Label>
                                <Input
                                    name="episode"
                                    type="number"
                                    value={series ? (series.count + 1) : 1}
                                />
                            </FormGroup>

                            <FormGroup className={"flex-grow-0"}>
                                <Label for="explicit">
                                    <span id="explicit">Is explicit?</span>
                                </Label>
                                <br/>
                                <MyButtonGroup className={"mt-0"} funcPos={() => setExplicitSelected(true)}
                                               funcNeg={() => setExplicitSelected(false)}
                                               value={explicitSelected}/>
                            </FormGroup>
                        </div>

                        <FormGroup>
                            <Label for="episodeTitle">
                                <span id="episodeTitle">Episode Title</span>
                            </Label>
                            <Input
                                required={true}
                                id="inputTitle"
                                name="title"
                                type="text"
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="description">
                                <span id="description">Description <sub>Text form</sub></span>
                            </Label>
                            <Input
                                required={true}
                                name="description"
                                type="textarea"
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="content">
                                <span id="content">Content  - Like description, but with HTML</span>
                            </Label>
                            <Input
                                required={true}
                                name="content"
                                type="textarea"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="published">
                                <span id="published">Published</span>
                            </Label>
                            <Input
                                required={true}
                                name="pubDate"
                                type="date"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="keywords">
                                Keywords
                            </Label>

                            <p>
                                Example: <samp>crime, thriller, mystery, detective, maritim, radio play...</samp>
                            </p>
                            <InputTag
                                tags={tags}
                                setTags={setTags}
                                name="tags"
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="type">
                                Episode Type
                            </Label>

                            <Input type="select" className="mb-2"
                                   onChange={(e: ChangeEvent<HTMLInputElement>): void => showInfo(e.target.value)}
                                   name="episodeType"
                                   id="type">
                                <option defaultChecked={true} value="">Select episode type</option>
                                <option value="full">Full</option>
                                <option value="trailer">Trailer</option>
                                <option value="bonus">Bonus</option>
                            </Input>
                            <Collapse isOpen={isTypeOpen}>
                                <Alert color="light" className="" id="typeInfo">
                                    {typeSelected !== '' ? (
                                        <span dangerouslySetInnerHTML={{__html: typeSelected}}/>
                                    ) : (
                                        'Select a type to see the explanation'
                                    )}
                                </Alert>
                            </Collapse>
                        </FormGroup>
                        <FormGroup>
                            <Label for="author">
                                <span id="author">Author</span>
                            </Label>
                            <Input
                                required={true}
                                name="author"
                                type="text"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="coverUrl">
                                <span id="coverUrl">Cover Url</span>
                            </Label>
                            <Input
                                required={true}
                                name="coverUrl"
                                value={coverUrl}
                                type="text"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="fileUrl">
                                <span id="fileUrl">File URL</span>
                            </Label>
                            <Input
                                required={true}
                                name="fileUrl"
                                type="text"
                                value={fileUrl}
                            />
                        </FormGroup>

                        <div className={"d-inline-flex flex-wrap w-100"}>
                            <FormGroup className={"flex-fill me-2"}>
                                <Label for="filesize">
                                    <span id="filesize">Filesize in Bytes</span>
                                </Label>
                                <Input
                                    name="fileLength"
                                    type="number"
                                    value={filesize}
                                />
                            </FormGroup>
                            <FormGroup className={"flex-fill me-2"}>
                                <Label for="duration">
                                    <span id="duration">Duration in Sec.</span>
                                </Label>
                                <Input
                                    name="duration"
                                    type="number"
                                    value={duration}
                                />
                            </FormGroup>
                        </div>

                        <input type="hidden" name="coverUuid" value={coverUuid}/>
                        <input type="hidden" name="radioplayUuid" value={radioplayUuid}/>

                        {state && (
                            <Alert color={state.success ? 'success' : 'danger'} className="mt-3">
                                {state.message}
                            </Alert>
                        )}

                        <Button type={"submit"} color="primary" disabled={isPending}>
                            {isPending ? 'Submitting...' : 'Create'}
                        </Button>
                    </Form>
                </CardBody>
            </Card>

            <Row className={"mt-2"}>
                <Col>
                    <Card className="">
                        <CardHeader><h5>Cover upload</h5></CardHeader>
                        <CardBody>
                            <Suspense fallback={<div>Loading cover upload...</div>}>
                                <ShowBucket seriesPromise={seriesData}/>
                                <DropUpload onFilesUploaded={handleCoverChange} fileType={"cover"} bucket={bucket}/>
                            </Suspense>
                        </CardBody>
                    </Card>
                </Col>

                <Col className={"mt-2 mt-xl-0"}>
                    <Card className="">
                        <CardHeader><h5>File upload</h5></CardHeader>
                        <CardBody>
                            <Suspense fallback={<div>Loading file upload...</div>}>
                                <ShowBucket seriesPromise={seriesData}/>
                                <DropUpload onFilesUploaded={handleRadioplayChange} fileType={"radioplay"}
                                            bucket={bucket}/>
                            </Suspense>
                        </CardBody>
                    </Card>
                </Col>
            </Row>
        </Container>
    )
}

export default EpisodeCreate as FC;
