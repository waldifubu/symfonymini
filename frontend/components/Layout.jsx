import Seo from "./Seo.jsx";

const Layout =({children, seo}) =>{
    return(
        <div className="container">
            <Seo seo={seo}></Seo>

            {children}
        </div>
    )
}

export default Layout;

/*
                <meta property="og:type" content={type} />
                <meta property="og:title" content={title} />
                <meta property="og:description" content={description} />
                <meta name="twitter:creator" content={name} />}
                <meta name="twitter:card" content={type} />
                <meta name="twitter:title" content={title} />
                <meta name="twitter:description" content={description} />

 */