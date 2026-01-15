import React, {ChangeEvent, FC, useState} from "react";
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
    Row
} from "reactstrap";
import MyButtonGroup from "../components/UtilComponents/MyButtonGroup";
import InputTag from "../components/UtilComponents/InputTag";
import {Tag} from "react-tag-input";
import DropUpload, {FileMetadata} from "../components/UtilComponents/DropUpload";

const EpisodeCreate: React.FC = () => {
    const [typeSelected, setTypeSelected] = useState('');
    const [isTypeOpen, setTypeIsOpen] = useState(false);
    const [explicitSelected, setExplicitSelected] = useState(false);
    const [tags, setTags] = React.useState<Tag[]>([]);
    const [coverUrl, setCoverUrl] = React.useState<string>("");
    const [fileUrl, setFileUrl] = React.useState<string>("");
    const [duration, setDuration] = React.useState<string>("");

    function showInfo(value: string) {
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
        }
    };

    const handleRadioplayChange = (metadata: FileMetadata[]): void => {
        if (metadata.length > 0) {
            const radioplayData: FileMetadata = metadata[0];
            // setCoverUrl(coverData.url);
        }
    };

    const handleDelete = (i: number) => {
        setTags(tags.filter((tag, index) => index !== i));
    };

    const handleAddition = (tag: Tag) => {
        setTags([...tags, tag]);
    };

    const onClearAll = () => {
        setTags([]);
    };

    return (
        <Container>
            <Card>
                <CardHeader><h2>New Episode</h2></CardHeader>
                <CardBody>
                    <Form method="post" autoComplete="on">
                        <div className={"d-inline-flex flex-wrap w-50"}>
                            <FormGroup className={"flex-fill me-2"}>
                                <Label for="no">
                                    <span id="no">Episode No. (Number)</span>
                                </Label>
                                <Input
                                    name="episode"
                                    type="number"
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
                                name="description"
                                type="textarea"
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="content">
                                <span id="content">Content  - Like description, but with HTML</span>
                            </Label>
                            <Input
                                name="content"
                                type="textarea"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="published">
                                <span id="published">Published</span>
                            </Label>
                            <Input
                                name="pubDate"
                                type="date"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="keywords">
                                Keywords
                            </Label>

                            <p>
                                Example: <samp>crime, thriller, mystery, detective, maritim, radio play...</samp></p>
                            <InputTag
                                tags={tags}
                                handleDelete={handleDelete}
                                handleAddition={handleAddition}
                                onClearAll={onClearAll}
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
                                name="author"
                                type="text"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="coverUrl">
                                <span id="coverUrl">Cover Url</span>
                            </Label>
                            <Input
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


                        <Button type={"submit"} color="primary">
                            Create
                        </Button>
                    </Form>
                </CardBody>
            </Card>

            <Row className={"mt-2"}>
                <Col>
                    <Card className="">
                        <CardHeader><h5>Cover upload</h5></CardHeader>
                        <CardBody>
                            {/* Pass fileMetadata as prop and receive updates via callback */}
                            <DropUpload onFilesUploaded={handleCoverChange}/>
                        </CardBody>
                    </Card>
                </Col>

                <Col>
                    <Card className="">
                        <CardHeader><h5>File upload</h5></CardHeader>
                        <CardBody>
                            <DropUpload onFilesUploaded={handleRadioplayChange}/>
                        </CardBody>
                    </Card>
                </Col>
            </Row>
        </Container>
    )
}

export default EpisodeCreate as FC;
