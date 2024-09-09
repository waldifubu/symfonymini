import './app.css';
import React from 'react';
import {Overview} from "./Overview";
//import * as bootstrap from 'bootstrap';
// import { Container, Collapse, Popover, Toast, Tooltip, Alert, Modal, Dropdown } from bootstrap;

export const App = () => {
    return (
        <div className="App">
            <header className="App-header container">
               <h2>Podcast Management</h2>

                <Overview/>
            </header>
        </div>
    );
}

