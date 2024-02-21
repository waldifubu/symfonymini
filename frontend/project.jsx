import {StrictMode, Suspense, lazy} from 'react';
import {createRoot} from "react-dom/client";
import {
    createBrowserRouter, RouterProvider,
} from "react-router-dom";
import {HelmetProvider} from "react-helmet-async";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min';

// import NotFound from "./pages/NotFound.jsx";
import Loading from "./components/Loading.jsx";
import NotFound from "./pages/NotFound.jsx";

const ProjectList = lazy(() => import("./pages/ProjectList.jsx"));
const ProjectCreate = lazy(() => import ("./pages/ProjectCreate.jsx"));
const ProjectEdit = lazy(() => import("./pages/ProjectEdit.jsx"));
const ProjectShow = lazy(() => import ("./pages/ProjectShow.jsx"));
const helmetContext = {};

/*
<Routes>
                        <Route exact path="/" element={<Suspense fallback={<Loading/>}><ProjectList/></Suspense>}/>
                        <Route path="/create" element={<Suspense><ProjectCreate/></Suspense>}/>
                        <Route path="/edit/:slug" element={<Suspense><ProjectEdit/></Suspense>}/>                        <Route path="/show/:slug" element={<Suspense><ProjectShow/></Suspense>}/>
                        <Route path="*" element={<Suspense><NotFound/></Suspense>}/>
                    </Routes>
 */

const router = createBrowserRouter([
        {
            path: "/",
            element: <Suspense fallback={<Loading/>}><ProjectList/></Suspense>
        },
        {
            path: "/create",
            element: <Suspense fallback={<Loading/>}><ProjectCreate/></Suspense>
        },
        {
            path: "/edit/:slug",
            element: <Suspense fallback={<Loading/>}><ProjectEdit/></Suspense>
        },
        {
            path: "/show/:slug",
            element: <Suspense fallback={<Loading/>}><ProjectShow/></Suspense>
        },
        {
            path: "*",
            element: <NotFound/>,
            errorElement: <NotFound/>

        }
    ])
;

function Main() {
    return (
        <>
            <HelmetProvider context={helmetContext}>
                <RouterProvider router={router}/>
            </HelmetProvider>
        </>
    );
}

createRoot(document.getElementById('root')).render(
    <StrictMode>
        <Main/>
    </StrictMode>
)