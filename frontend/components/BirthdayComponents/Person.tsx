import React from "react";


const style = {
    // display: 'flex',
    // justifyContent: 'center',
    // alignItems: 'center',
    height: '10vh',
    borderRadius: '50%',
}

// @ts-ignore
const Person = ({id, image, name, age}) => {
    return (
        <div className="person">
            <article key={id} className="person">
                <img src={image} alt={name} style={style}/>
                <div>
                    <h4>{name}</h4>
                    <p>{age} years</p>
                </div>
            </article>
        </div>
    )
}

export default Person;