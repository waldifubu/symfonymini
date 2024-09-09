import './app.css';
import React from 'react';
import {Overview} from "./Overview";
import {Alert} from "reactstrap";
//import * as bootstrap from 'bootstrap';
// import { Container, Collapse, Popover, Toast, Tooltip, Alert, Modal, Dropdown } from bootstrap;

export const App = () => {
    return (
        <div className="App">
            <header className="App-header container">
                <Alert className="mt-2" color="light"><h2>Podcast Management</h2></Alert>


                <Overview/>
            </header>
        </div>
    );
}

