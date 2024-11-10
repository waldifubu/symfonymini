import './components/app.css';
import React, {lazy, Suspense} from 'react';
import {createBrowserRouter, Outlet, RouterProvider} from "react-router-dom";
import 'bootstrap/dist/css/bootstrap.min.css';
import MyNavbar from "./components/MyNavbar";


const SeriesList: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import("./pages/SeriesList"));
const SeriesCreate: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import ("./pages/SeriesCreate"));
const People: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import ("./pages/Example"));
const People2: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import ("./components/ExampleReducer"));
const BirthdayReminder: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import("./pages/BirthdayReminder"));
const Tours: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import("./pages/ToursPage"));

const routes = [
    {
        path: "/",
        element: <Suspense fallback={<div>Laden ...</div>}><SeriesList/></Suspense>
    },
    {
        path: "/create",
        element: <Suspense fallback={<div>laden2 ...</div>}><SeriesCreate/></Suspense>
    },
    {
        path: "/people",
        element: <Suspense fallback={<div>laden4 ...</div>}><People/></Suspense>
    },
    {
        path: "/people2",
        element: <Suspense fallback={<div>laden3 ...</div>}><People2/></Suspense>
    },
    {
        path: "/birthday",
        element: <Suspense fallback={<div>Loading</div>}><BirthdayReminder/></Suspense>
    },
    {
        path: "/tours",
        element: <Suspense fallback={<div>Loading</div>}><Tours/></Suspense>
    }
]

const router = createBrowserRouter([
    {
        path: "/",
        element: <><MyNavbar/><Outlet/></>,
        children: routes
    }
]);

/*
const router = createBrowserRouter(
    createRoutesFromElements(
        <Route path="/" element={<App />}>
            <Route path="/" element={<Home />} />
            <Route path="/about" element={<About />} />
            <Route path="/projects" element={<Projects />} />
            <Route path="/contact" element={<Contact />} />
        </Route>
    )
);


 <RouterProvider router={router}>
        <App />
    </RouterProvider>
 */

export const App = () => {
    //const {name} = useGlobalContext();

    return (
        <div className="App">
            <header className="App-header">
                <RouterProvider router={router}>
                </RouterProvider>
            </header>
        </div>
    );
}

