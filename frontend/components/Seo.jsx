import {Helmet} from "react-helmet-async";
import React from "react";

function Seo({seo}) {
    return (
        <Helmet>
            <html lang="en"/>
            <title>{seo.title}</title>
            <meta name='description' content={seo.description}/>
            <meta name="theme-color" content="#E6E6FA"/>
        </Helmet>
    )
}

export default Seo;