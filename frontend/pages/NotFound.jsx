import {Link, useRouteError} from "react-router-dom";

export default function NotFound() {
    const error = useRouteError();
    // console.error(error);

    if (error.status === 404) {
        return <>
            <div>
                <h3>Ohh!!</h3>
                <p>We are not able to find the page for the given Url</p>
                <Link to='/'>Navigate Home </Link>
            </div>
        </>
    }
    return (
        <div id="error-page">
            <h1>Oops!</h1>
            <p>Sorry, an unexpected error has occurred.</p>
            <p>
                <i>{error!=undefined && (error.statusText || error.message)}</i>
            </p>
        </div>
    );
}