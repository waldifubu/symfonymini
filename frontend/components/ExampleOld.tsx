import {useReducer, useState} from "react";
import {Button, Card, Container} from "reactstrap";

const ExampleOld = () => {

    const data: { id: number, name: string }[] = [
        {"id": 1, "name": "Alice"},
        {"id": 2, "name": "Bob"},
        {"id": 3, "name": "Charlie"},
        {"id": 4, "name": "Dora"}
    ];

    const defaultState = {people: data, isLoading: false};
    const [people, setPeople] = useState(data);

    const CLEAR_LIST: string = 'CLEAR_LIST';
    const RESET_LIST: string = 'RESET_LIST';
    const REMOVE_ITEM: string = 'REMOVE_ITEM';

    // @ts-ignore
    const reducer: (state: object, action: any) => ({ people: any[] }) = (state: object, action: any) => {
        switch (action.type) {
            case CLEAR_LIST:
                return {...state, people: []}
            case RESET_LIST:
                return {...state, people: data}
            case REMOVE_ITEM:
                // @ts-ignore
                let newPeople = state.people.filter((person) => person.id !== action.payload.id);
                return {...state, people: newPeople};
        }
    }

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
                    <ul style={{listStyle: 'none'}}>
                        {state.people.map((person: { id: number, name: string }) => {
                            const {id, name} = person;
                            return (
                                <li key={id}>
                                    <h4>{name}</h4>
                                    <Button color="primary" onClick={()=> removeItem(id)}>remove</Button>
                                </li>
                            )
                        })}
                    </ul>
                    {state.people.length < 1 ? (
                            <Button onClick={resetList} color="danger">RESET</Button>
                        ) :
                        <Button onClick={clearList} color="warning">CLEAR</Button>
                    }
                </Card>
            </Container>
        </>
    )
}

export default ExampleOld