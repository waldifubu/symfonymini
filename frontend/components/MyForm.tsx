import {Button, Form, FormGroup, Input, Label} from "reactstrap";
import React, {useReducer, useState} from "react";
import reducer from "./reducer";
import {data} from "../pages/Example";

// @ts-ignore
const MyForm = ({addPerson}) => {
    const [name, setName] = useState('')


    // @ts-ignore
    const handleSubmit = (e) => {
        e.preventDefault();

        //addName(name)
        addPerson(name);
        console.log('submitted')
        setName('');
    }

    return (
        <Form onSubmit={handleSubmit}>
            <FormGroup>
                <Label for="exampleEmail">
                    Add Name
                </Label>
                <Input
                    id="exampleEmail"
                    name="name"
                    placeholder=""
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                />
            </FormGroup>
            <Button>Add</Button>
            {/*onClick={() => addItem(name)}*/}
        </Form>
    )
}

export default MyForm