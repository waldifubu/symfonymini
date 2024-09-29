import React, {useReducer, useState} from "react";
import {Button, Card, Container} from "reactstrap";
import reducer from "../components/reducer";
import {CLEAR_LIST, REMOVE_ITEM, RESET_LIST} from "../components/actions"
import {Link} from "react-router-dom";


export const data: { id: number, name: string }[] = [
    {"id": 1, "name": "Alice"},
    {"id": 2, "name": "Bob"},
    {"id": 3, "name": "Charlie"},
    {"id": 4, "name": "Dora"}
];

const ExampleReducer = () => {

    const defaultState = {people: data, isLoading: false};
    const [people, setPeople] = useState(data);
    const [state, dispatch] = useReducer(reducer, defaultState);

    const clearList = () => {
        // @ts-ignore
        dispatch({type: CLEAR_LIST})
    }
    const resetList = () => {
        // @ts-ignore
        dispatch({type: RESET_LIST})
    }

    const removeItem = (id: number) => {
        // @ts-ignore
        dispatch({type: REMOVE_ITEM, payload: {id}})
    }

    return (
        <>
            <Container>
                <Card className="text-center">
                    <h2>People</h2>



                    <ul className="ps-0" style={{listStyle: 'none'}}>
                        {state.people.map((person: { id: number, name: string }) => {
                            const {id, name} = person;
                            return (
                                <li className="" key={id}>
                                    <h4>{name}</h4>
                                    <Button color="primary" onClick={() => removeItem(id)}>remove</Button>
                                </li>
                            )
                        })}
                    </ul>
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

export default ExampleReducer