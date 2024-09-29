import React, {memo, useReducer} from "react";
import {data} from "../pages/Example";
import reducer from "./reducer";
import {Button} from "reactstrap";
import {REMOVE_ITEM} from "./actions";

// @ts-ignore
const MyList = ({people, removePerson}) => {
    const defaultState = {people: data, isLoading: false};
    const [state, dispatch] = useReducer(reducer, defaultState);


    return (
        <ul className="ps-0" style={{listStyle: 'none'}}>
            {people.map((person: { id: number, name: string }) => {
                const {id, name} = person;
                return (
                    <li className="" key={id}>
                        <h4>{name}</h4>
                        <Button color="primary" onClick={() => removePerson(id)}>remove</Button>
                    </li>
                )
            })}
        </ul>
    )
}

export default memo(MyList);