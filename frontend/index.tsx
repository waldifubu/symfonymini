import React, {StrictMode} from 'react';
import {createRoot} from 'react-dom/client';
import './index.css';
import {App} from './App';
import AppContext from "./components/GlobalContext";

const root = createRoot(document.getElementById('root') as HTMLElement);

// Creates Context provider which uses a certain context (GlobalContext)
root.render(
    <StrictMode>
        <AppContext>
            <App/>
        </AppContext>
    </StrictMode>
);

