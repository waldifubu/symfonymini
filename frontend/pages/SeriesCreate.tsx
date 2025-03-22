import React, {ChangeEvent, FC, useEffect, useState} from "react";
import {
    Alert,
    Badge,
    Button,
    Card,
    CardBody,
    CardHeader,
    Collapse,
    Container,
    Form,
    FormGroup,
    Input,
    Label,
    List
} from "reactstrap";
import axios from "axios";
import Swal from 'sweetalert2'
import {Link, useNavigate} from "react-router-dom";
import TooltipItem from "../components/UtilComponents/TooltipItem";
import MyButtonGroup from "../components/UtilComponents/MyButtonGroup";
import InputTag from "../components/UtilComponents/InputTag";
import {Tag} from "react-tag-input";

const SeriesCreate: React.FC = () => {
    const navigate = useNavigate();
    const [isLoading, setIsLoading] = useState(true);
    const refContainer = React.useRef<HTMLInputElement>(null);
    const [lockSelected, setLockSelected] = useState(true);
    const [blockSelected, setBlockSelected] = useState(true);
    const [explicitSelected, setExplicitSelected] = useState(false);
    const [completeSelected, setCompleteSelected] = useState(false);
    const [typeSelected, setTypeSelected] = useState('');
    const [isTypeOpen, setTypeIsOpen] = useState(false);
    const [tags, setTags] = React.useState<Tag[]>([]);

    const [cSelected, setCSelected] = useState([]);
    // const [description, setDescription] = useState('')
    const [mainCategories, setMainCategories] = useState<Category[]>([])
    const [subCategories, setSubCategories] = useState<Category[]>([])

    const frequencyListStyle = {
        cursor: 'pointer',
    }

    const blockText: string = 'If you want your show removed from the Apple directory, use this tag.\n' +
        '\n' +
        'Specifying the <itunes:block> tag with a Yes value, prevents the entire podcast from appearing in Apple Podcasts.\n' +
        '\n' +
        'Specifying any value other than Yes has no effect.';

    const lockText: string = 'This tag may be set to yes or no. The purpose is to tell other podcast hosting platforms whether they are allowed to import this feed. A value of yes means that any attempt to import this feed into a new platform should be rejected.';

    const explicitText: string = 'The explicit value can be one of the following:\n' +
        '\n' +
        'True. If you specify true, indicating the presence of explicit content, Apple Podcasts displays an Explicit parental advisory graphic for your podcast.\n' +
        '\n' +
        'Podcasts containing explicit material aren’t available in some Apple Podcasts territories.\n' +
        '\n' +
        'False. If you specify false, indicating that your podcast doesn’t contain explicit language or adult content, Apple Podcasts displays a Clean parental advisory graphic for your podcast.';

    const completeText: string = 'The podcast update status.\n' +
        '\n' +
        'If you will never publish another episode to your show, use this tag.\n' +
        '\n' +
        'Specifying the <itunes:complete> tag with a Yes value indicates that a podcast is complete and you will not post any more episodes in the future.\n' +
        '\n' +
        'Specifying any value other than Yes has no effect.';

    interface Category {
        value: string;
        name: string;
    }

    const handleDelete = (i: number) => {
        setTags(tags.filter((tag, index) => index !== i));
    };

    const handleAddition = (tag: Tag) => {
        setTags([...tags, tag]);
    };

    const onClearAll = () => {
        setTags([]);
    };

    const handleSubmit = (e: any): void => {
        e.preventDefault()
        const formData: FormData = new FormData(e.currentTarget)
        formData.append('locked', lockSelected ? '1' : '0')
        formData.append('blocked', blockSelected ? '1' : '0')
        formData.append('explicit', explicitSelected ? '1' : '0')
        formData.append('complete', completeSelected ? '1' : '0')
        formData.append('keywords', tags.map(tag => tag.text).join(', '))

        // const tagsString = tags.map(tag => tag.text).join(', ');
        // formData.append('keywords', tagsString)

        console.log([...formData.entries()])
        // return;
        // const newPodcast: { [p: string]: File | string } = Object.fromEntries(formData)
        // console.log(refContainer.current?.value)
        axios.post('/api/series', formData)
            .then(function (response) {
                // console.log(response);
                if (response.status === 201) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Podcast created',
                        showConfirmButton: false,
                        timer: 2500
                    }).then(() => {
                        navigate('/')
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: response.statusText,
                        showConfirmButton: false,
                        timer: 500
                    }).then(() => {
                        navigate('/')
                    });
                }
            }).catch(function (error) {
            console.log(error.response.data)
            Swal.fire({
                icon: 'error',
                title: error + ', Response: ' + error.response.data,
                showConfirmButton: false,
                timer: 3100
            })
        });
    }

    const fetchMainCategories = () => {
        axios.get('/api/category')
            .then(function (response) {
                const transformedData: {
                    value: string,
                    name: string
                }[] = Object.entries(response.data).map(([value, name]) => ({
                    value,
                    name: name as string,
                }));
                setIsLoading(false); // Data has been loaded
                setMainCategories(transformedData); // Set the transformed data to state
            })
            .catch(function (error) {
                setIsLoading(false); // Data has been loaded
                console.log(error)
            })
    };

    useEffect(() => {
        fetchMainCategories();
        window.scrollTo(0, 0);
        document.getElementById("inputTitle")?.focus();
    }, []);

    function processDesc(e: any) {
        // setDescription(e.target.value)
    }

    function fetchSubCategories(category: string) {
        setIsLoading(true); // Data is loading
        axios.get(`/api/category/${category}`)
            .then(function (response) {
                // console.log(response.data)
                const transformedData: {
                    value: string,
                    name: string
                }[] = Object.entries(response.data).map(([value, name]) => ({
                    value,
                    name: name as string,
                }));
                setIsLoading(false); // Data has been loaded
                setSubCategories(transformedData); // Set the transformed data to state
            })
            .catch(function (error) {
                setIsLoading(false); // Data has been loaded
                console.log(error)
            })
    }

    const onCheckboxBtnClick = (selected: any) => {
        // @ts-ignore
        const index = cSelected.indexOf(selected);
        if (index < 0) {
            // @ts-ignore
            cSelected.push(selected);
        } else {
            cSelected.splice(index, 1);
        }
        setCSelected([...cSelected]);
    };

    function showInfo(value: string) {
        setTypeIsOpen(value !== '');

        if (value == 'serial') {
            setTypeSelected('<strong>Serial.</strong> Specify serial when episodes are intended to be consumed in sequential order. Apple Podcasts will present the oldest episodes first and display the episode numbers (required) of each episode. If organized into seasons, the newest season will be presented first and <itunes:episode> numbers must be given for each episode.' +
                '<p>' + 'Each show type has different behavior for automatic downloads.</p>');
        } else if (value == 'episodic') {
            setTypeSelected('<strong>Episodic (default)</strong>. Specify episodic when episodes are intended to be consumed without any specific order. Apple Podcasts will present newest episodes first and display the publish date (required) of each episode. If organized into seasons, the newest season will be presented first - otherwise, episodes will be grouped by year published, newest first.' +
                '<p>' + 'For new subscribers, Apple Podcasts adds the newest, most recent episode in their Library.</p>');
        }
    }

    const setFrequent = (value: string) => {
        let frequency = document.getElementById('frequency') as HTMLInputElement;
        frequency.value = value;
    }

    const setPublishedDate = () => {
        let pubDate = document.getElementById('pubDate') as HTMLInputElement;

        const date = new Date();
        const options: Intl.DateTimeFormatOptions = {
            weekday: 'short',
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZoneName: 'short',
            hour12: false
        };

        pubDate.value = date.toLocaleString('en-US', options);
    };

    // @ts-ignore
    return (
        <Container>
            <Card>
                <CardHeader><h2>Create Podcast</h2></CardHeader>
                <CardBody>
                    <Form method="post" autoComplete="on" onSubmit={handleSubmit}>
                        <FormGroup>
                            <Label for="podcastTitle">
                                <span id="podcastTitle">Podcast Title</span>
                                <TooltipItem position={'bottom'} target={'podcastTitle'}
                                             text={'It’s important to have a clear, concise name for your podcast. Make your title specific. A show titled Our Community Bulletin is too vague to attract many subscribers, no matter how compelling the content.\n' +
                                                 '\n' +
                                                 'Pay close attention to the title as Apple Podcasts uses this field for search.\n' +
                                                 '\n' +
                                                 'If you include a long list of keywords in an attempt to game podcast search, your show may be removed from the Apple directory.'}/>
                            </Label>
                            <Input
                                id="inputTitle"
                                name="title"
                                placeholder=""
                                type="text"
                                innerRef={refContainer}
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="description">
                                <span id="description">Description</span>
                                <TooltipItem position={'top'} target={'description'}
                                             text={'Where description is text containing one or more sentences describing your podcast to potential listeners. The maximum amount of text allowed for this tag is 4000 bytes.\n' +
                                                 'To include links in your description or rich HTML, adhere to the following technical guidelines: enclose all portions of your XML that contain embedded HTML in a CDATA section to prevent formatting issues, and to ensure proper link functionality. For example:\n'
                                                 + '<![CDATA[<a href="http://www.example.com">Visit our website</a>]]>'}/>
                            </Label>
                            <Input onChange={processDesc}
                                   id="description"
                                   name="description"
                                   placeholder=""
                                   type="textarea"
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="author">
                                <span id="author">Author</span>
                                <TooltipItem position={'top'} target={'author'}
                                             text={'The group responsible for creating the show.\n' +
                                                 '\n' +
                                                 'Show author most often refers to the parent company or network of a podcast, but it can also be used to identify the host(s) if none exists.\n' +
                                                 '\n' +
                                                 'Author information is especially useful if a company or organization publishes multiple podcasts.'}/>
                            </Label>
                            <Input
                                id="author"
                                name="author"
                                placeholder=""
                                type="text"
                            />
                        </FormGroup>

                        <div className={"d-flex justify-content-between"}>
                            <FormGroup>
                                <Label for="locked">
                                    <span id="locked">Is locked? &nbsp;</span>
                                    <TooltipItem position={'top'} target={'locked'}
                                                 text={lockText}/>
                                </Label>

                                <MyButtonGroup funcPos={() => setLockSelected(true)}
                                               funcNeg={() => setLockSelected(false)}
                                               value={lockSelected}/>
                            </FormGroup>

                            <FormGroup>
                                <span id="blocked">&nbsp; Is Blocked? &nbsp;</span>
                                <Label for="blocked">
                                    <TooltipItem position={'top'} target={'blocked'}
                                                 text={blockText}/>
                                </Label>

                                <MyButtonGroup funcPos={() => setBlockSelected(true)}
                                               funcNeg={() => setBlockSelected(false)}
                                               value={blockSelected}/>
                            </FormGroup>

                            <FormGroup>
                                <Label for="explicit">
                                    <span id="explicit">&nbsp; Is explicit? &nbsp;</span>
                                    <TooltipItem position={'top'} target={'explicit'}
                                                 text={explicitText}/>
                                </Label>

                                <MyButtonGroup funcPos={() => setExplicitSelected(true)}
                                               funcNeg={() => setExplicitSelected(false)}
                                               value={explicitSelected}/>
                            </FormGroup>

                            <FormGroup>
                                <Label for="complete">
                                    <span id="complete">&nbsp; Is complete? &nbsp;</span>
                                    <TooltipItem position={'top'} target={'complete'}
                                                 text={completeText}/>
                                </Label>

                                <MyButtonGroup funcPos={() => setCompleteSelected(true)}
                                               funcNeg={() => setCompleteSelected(false)}
                                               value={completeSelected}/>
                            </FormGroup>
                        </div>

                        <FormGroup>
                            <Label for="copyright">
                                Copyright
                            </Label>
                            <Input
                                name="copyright"
                                placeholder=""
                                type="text"
                            />
                        </FormGroup>

                        <div className={"d-flex w-100"}>
                            <FormGroup className={"flex-fill me-2"}>
                                <Label for="language">
                                    <span id="language">Language</span>
                                    <TooltipItem position={'top'} target={'language'}
                                                 text={'Because Apple Podcasts is available in territories around the world, it is critical to specify the language of a podcast. Apple Podcasts only supports values from the ISO 639 list (two-letter language codes, with some possible modifiers, such as "fr-ca").\n' +
                                                     '\n' +
                                                     'Invalid language codes will cause your feed to fail Apple validation.'}/>
                                </Label>
                                <Input
                                    className="w-100"
                                    name="language"
                                    placeholder="de-de"
                                    type="text"
                                />
                            </FormGroup>

                            <FormGroup className={"flex-fill"}>
                                <Label for="ttl">
                                    <span id="ttl">TTL</span>
                                    <TooltipItem position={'top'} target={'ttl'}
                                                 text={'Element specifies the number of minutes the feed can stay cached before refreshing it from the source.'}/>
                                </Label>
                                <Input
                                    className={"w-100"}
                                    name="ttl"
                                    defaultValue="60"
                                    type="text"
                                />
                            </FormGroup>
                        </div>

                        <FormGroup>
                            <Label for="cover">
                                Cover URL (optional)
                            </Label>
                            <Input
                                name="cover"
                                placeholder="http://"
                                type="text"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="type">
                                Type
                            </Label>

                            <Input type="select" className="mb-2"
                                   onChange={(e: ChangeEvent<HTMLInputElement>): void => showInfo(e.target.value)}
                                   name="type"
                                   id="type">
                                <option value="">Select a type</option>
                                <option value="serial">Serial</option>
                                <option value="episodic">Episodic</option>
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
                            <Label for="owner">
                                Owner
                            </Label>
                            <Input
                                name="owner"
                                placeholder=""
                                type="text"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="ownerEmail">
                                Owner Email (optional)
                            </Label>
                            <Input
                                name="ownerEmail"
                                placeholder=""
                                type="text"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="category">
                                Category
                            </Label>
                            <Input type="select" name="mainCategory" id="category"
                                   onChange={(e: ChangeEvent<HTMLInputElement>) => fetchSubCategories(e.target.value)}>
                                {
                                    <>
                                        <option value="">Select a category</option>
                                        {mainCategories.map((category, index) => (
                                            <option key={index} value={category.value}>
                                                {category.name}
                                            </option>
                                        ))}
                                    </>
                                }
                            </Input>
                        </FormGroup>

                        <FormGroup>
                            <Label for="subCategory">
                                Subcategory
                            </Label>
                            <Input type="select" name="subCategory" id="subcategory" disabled={isLoading}
                                   style={{
                                           // Base style (applies always)
                                           color: "black",
                                           // Conditional styles based on isLoading
                                           ...(isLoading
                                               ? {
                                                   color: "#ff0000", // Gray background when loading
                                                   cursor: "wait", // Show loading cursor
                                                   opacity: 0.7, // Fade appearance
                                               }
                                               : {
                                                   // color: "black", // Normal text color
                                                   // backgroundColor: "white", // Normal background
                                                   // cursor: "default", // Default cursor
                                               }),
                                       }}
                                >
                                {isLoading ? (
                                    <option>Loading...</option>
                                ) : (
                                    <>
                                        <option value="">Select a category</option>
                                        {subCategories.map((category, index) => (
                                            <option key={index} value={category.value}>
                                                {category.name}
                                            </option>
                                        ))}
                                    </>
                                )}
                            </Input>
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
                            <Label for="pubDate">
                                Published Date <sub style={{cursor: 'pointer'}}
                                                    onClick={() => setPublishedDate()}>(now)</sub>
                            </Label>
                            <Input
                                id="pubDate"
                                name="published"
                                placeholder="Fri, 01 Jan 2025 06:00:00 PDT"
                                type="text"
                            />
                        </FormGroup>

                        <FormGroup>
                            <Label for="frequency">
                                Frequency of updates
                            </Label>
                            <Input
                                id="frequency"
                                name="frequency"
                                placeholder="FREQ=MONTHLY"
                                type="text"
                            />
                            Examples:
                            <List type="inline" tag="ul" id="frequencyList">
                                <li onClick={() => setFrequent('FREQ=DAILY')} style={frequencyListStyle}>
                                    <Badge
                                        color="info"
                                        pill
                                    >
                                        FREQ=DAILY
                                    </Badge>
                                    means Daily
                                </li>
                                <li onClick={() => setFrequent('FREQ=WEEKLY')} style={frequencyListStyle}>
                                    <Badge color="info" pill>
                                        FREQ=WEEKLY
                                    </Badge>
                                    means Weekly
                                </li>
                                <li onClick={() => setFrequent('FREQ=MONTHLY')} style={frequencyListStyle}>
                                    <Badge color="info" pill>
                                        FREQ=MONTHLY
                                    </Badge>
                                    means Monthly
                                </li>
                                <li onClick={() => setFrequent('FREQ=YEARLY')} style={frequencyListStyle}>
                                    <Badge color="info" pill>
                                        FREQ=YEARLY
                                    </Badge>
                                    means Yearly
                                </li>
                                <li onClick={() => setFrequent('FREQ=WEEKLY;BYDAY=MO,TU,WE,TH,FR')}
                                    style={frequencyListStyle}>FREQ=WEEKLY;BYDAY=MO,TU,WE,TH,FR means Monday to Friday
                                </li>
                                <li onClick={() => setFrequent('FREQ=WEEKLY;BYDAY=MO,WE')}
                                    style={frequencyListStyle}>FREQ=WEEKLY;BYDAY=MO,WE means Monday and Wednesday
                                </li>
                                <li onClick={() => setFrequent('FREQ=WEEKLY;BYDAY=FR;BYMONTHDAY=13')}
                                    style={frequencyListStyle}>FREQ=WEEKLY;BYDAY=FR;BYMONTHDAY=13 means Friday the 13th
                                </li>
                                <li onClick={() => setFrequent('FREQ=YEARLY;BYDAY=+4TH;BYMONTH=11')}
                                    style={frequencyListStyle}>FREQ=YEARLY;BYDAY=+4TH;BYMONTH=11 means the fourth
                                    Thursday in November (Thanksgiving)
                                </li>
                                <li onClick={() => setFrequent('FREQ=WEEKLY;INTERVAL=2;BYDAY=MO;COUNT=10')}
                                    style={frequencyListStyle}>FREQ=WEEKLY;INTERVAL=2;BYDAY=MO;COUNT=10 means every
                                    other Monday for 10 weeks
                                </li>
                                <li onClick={() => setFrequent('FREQ=WEEKLY;UNTIL=20231231;BYDAY=MO')}
                                    style={frequencyListStyle}>FREQ=WEEKLY;UNTIL=20231231;BYDAY=MO means every Monday
                                    until the end of 2023
                                </li>
                                <li onClick={() => setFrequent('true')} style={frequencyListStyle}>
                                    <Badge color="info" pill>
                                        true
                                    </Badge>
                                    means no more updates (completed or stopped)
                                </li>
                            </List>

                        </FormGroup>

                        <Button type={"submit"} color="primary">
                            Create
                        </Button>
                    </Form>
                </CardBody>
            </Card>
            <br/>
            <Link
                className="btn btn-primary"
                to="/">Home
            </Link>
        </Container>
    )
}

export default SeriesCreate as FC;
