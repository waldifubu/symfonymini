import React from 'react';

// @ts-ignore
const Tour = ({tour}) => {
    return (
        <div key={tour.id}>
            <h2>{tour.name}</h2>
            <img src={tour.image} alt={tour.name}/>
            <p>{tour.info}</p>
            <p>{tour.price}</p>
        </div>

    );
};

export default Tour;
