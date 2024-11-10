import React from "react";
import Person from "./Person";

// @ts-ignore
const List = ({people}) => {
    return (
        <section>
            {people.map((person: { id: number; name: string; age: number; image: string; }) => {
                return (
                    <Person key={person.id} {...person}/>
                )
            })}
        </section>
    );
}

export default List;