import React, {useReducer, useState} from "react";
import {Button, Card, CardBody, Col, Container, Row} from "reactstrap";
import reducer from "../components/reducer";
import {CLEAR_LIST, REMOVE_ITEM, RESET_LIST} from "../components/actions"
import {Link} from "react-router-dom";
import MyForm from "../components/MyForm";
import MyList from "../components/MyList";


export const data: { id: number, name: string }[] = [
    {"id": 1, "name": "Alice"},
    {"id": 2, "name": "Bob"},
    {"id": 3, "name": "Charlie"},
    {"id": 4, "name": "Dora"}
];

const Example = () => {

    const defaultState = {people: data, isLoading: false};
    const [people, setPeople] = useState(data);
    const [state, dispatch] = useReducer(reducer, defaultState);

    const clearList = () => {
        // @ts-ignore
        dispatch({type: CLEAR_LIST})
        setPeople([]);
    }
    const resetList = () => {
        // @ts-ignore
        dispatch({type: RESET_LIST})
        setPeople(data)
    }

    const removePerson2 = (id: number) => {

        dispatch({type: REMOVE_ITEM, payload: {id}})
    }

    /*
    const addItem = (name:string) => {

        // @ts-ignore
        dispatch({type: ADD_ITEM, payload: {name}})
        setName('')
    }
*/
    function addName(name: string) {
        //setName(name)
        /*
        let newPeople = state.people;
        newPeople.push({
            "id": Math.floor(Math.random() * 10) + 5,
            "name": name
        })
        //let newPeople = state.people.filter((person) => person.id !== action.payload.id);
        return {...state, people: newPeople};
         */
    }

    const addPerson = (name: string) => {
        const fakeId = Date.now();
        const newPerson = {id: fakeId, name}
        setPeople([...people, newPerson])
    }


    const removePerson = (id: number) => {
        console.log(id)
        console.log(people)
        // dispatch({type: REMOVE_ITEM, payload: {id}})
        let newPeople = people.filter((person) => person.id !== id);

        // @ts-ignore
        setPeople([...people, ...newPeople]);
    }

    return (
        <>
            <Container>
                <Card className="text-center">
                    <h2>People</h2>
                    <Row xs="3">
                        <Col></Col>
                        <Col>
                            <Card>
                                <CardBody>
                                    <MyForm addPerson={addPerson}></MyForm>
                                </CardBody>
                            </Card>
                        </Col>
                        <Col></Col>
                    </Row>

                    <MyList people={people} removePerson={removePerson}/>

                    <br/>
                    <div>
                        {state.people.length < 1 ? (
                                <Button block={false} onClick={resetList} color="danger">RESET</Button>
                            ) :
                            <Button block={false} onClick={clearList} color="warning">CLEAR</Button>
                        }
                        <br/>
                        <Link
                            className="mt-1 btn btn-secondary"
                            to="/">Home
                        </Link>
                    </div>
                </Card>
            </Container>
        </>
    )
}

export default Example