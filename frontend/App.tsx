import './components/app.css';
import React, {lazy, Suspense} from 'react';
import {createBrowserRouter, Outlet, RouterProvider} from "react-router-dom";
import 'bootstrap/dist/css/bootstrap.min.css';
import MyNavbar from "./components/MyNavbar";

const SeriesList: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import("./pages/SeriesList"));
const SeriesCreate: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import ("./pages/SeriesCreate"));
const SeriesEdit: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import("./pages/SeriesEdit"));

const People: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import ("./pages/Example"));
const People2: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import ("./components/ExampleReducer"));
const BirthdayReminder: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import("./pages/BirthdayReminder"));
const Tours: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import("./pages/ToursPage"));
const EpisodeCreate: React.LazyExoticComponent<React.ComponentType<any>> = lazy(() => import("./pages/EpisodeCreate"));

const routes = [
    {
        path: "/",
        element: <SeriesList/>
    },
    {
        path: "/create",
        element: <SeriesCreate/>
    },
    {
        path: "/people",
        element: <People/>
    },
    {
        path: "/people2",
        element: <People2/>
    },
    {
        path: "/birthday",
        element: <BirthdayReminder/>
    },
    {
        path: "/tours",
        element: <Tours/>
    },
    {
        path: "/episode/add/:id",
        element: <EpisodeCreate/>
    },
    {
        path: "/series/edit/:id",
        element: <SeriesEdit/>
    }

]

const router = createBrowserRouter([
    {
        element: <><MyNavbar/><Suspense fallback={<div>Loading</div>}><Outlet/></Suspense></>,
        children: routes
    },
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
    return (
        <div className="App">
            <header className="App-header">
                <RouterProvider router={router}>
                </RouterProvider>
            </header>
        </div>
    );
}

