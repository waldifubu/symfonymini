import React, {FC, useEffect, useState} from "react";
import {
    Button,
    Card,
    CardBody,
    CardHeader,
    Container,
    Form,
    FormGroup,
    FormText,
    Input,
    Label,
    Table
} from "reactstrap";
import axios from "axios";
import Swal from 'sweetalert2'
import {Link} from "react-router-dom";

const SeriesCreate = () => {
    const refContainer = React.useRef<HTMLInputElement>(null);
    // const [description, setDescription] = useState('')

    const handleSubmit = (e: any): void => {
        e.preventDefault()
        // console.log(e.currentTarget)


        const formData: FormData = new FormData(e.currentTarget)
        // console.log([...formData.entries()])
        // const newPodcast: { [p: string]: File | string } = Object.fromEntries(formData)

        console.log(refContainer.current?.value)
    }

    useEffect(() => {
        // console.log(refContainer)
        // console.log(`Neue Beschreibung ${description}`)
    }, []);

    function processDesc(e: any) {
        // setDescription(e.target.value)
    }

    return (
        <Container>
            <Card>
                <CardHeader><h1>Create NEW Podcast</h1></CardHeader>
                <CardBody>
                    <Form method={"POST"} onSubmit={handleSubmit}>
                        <FormGroup>
                            <Label for="exampleEmail">
                                Podcast Name
                            </Label>
                            <Input
                                id="exampleEmail"
                                name="name"
                                placeholder=""
                                type="text"
                                innerRef={refContainer}
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="description">
                                Description
                            </Label>
                            <Input onChange={processDesc}
                                   id="description"
                                   name="description"
                                   placeholder=""
                                   type="text"
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="author">
                                Author
                            </Label>
                            <Input
                                id="author"
                                name="author"
                                placeholder=""
                                type="text"
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="keywords">
                                Keywords (Comma separated)
                            </Label>
                            <Input
                                id="keywords"
                                name="keywords"
                                placeholder="crime, thriller, mystery, detective, maritim, hoerspiel"
                                type="text"
                            />
                        </FormGroup>
                        <FormGroup>
                            <Label for="cover">
                                Cover URL
                            </Label>
                            <Input
                                id="cover"
                                name="cover"
                                placeholder="http://"
                                type="text"
                            />
                        </FormGroup>

                        <Button>
                            Submit
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