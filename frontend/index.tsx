import React, {StrictMode} from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import {App} from './App';
import AppContext from "./components/GlobalContext";
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            refetchOnWindowFocus: false,
            // refetchOnmount: false,
            // refetchOnReconnect: false,
            // retry: false,
            retry: 10,
            //staleTime: 5000 // the data will be considered fresh for 5000ms (5s)
            //  gcTime: 1000 * 60 * 60 * 24, // 24 hours
            staleTime: 5 * 60 * 1000,
        },
    },
});

// Creates Context provider which uses a certain context (GlobalContext)
ReactDOM.createRoot(document.getElementById('root') as HTMLElement).render(
    // <StrictMode>
        <AppContext>
            <QueryClientProvider client={queryClient}>
                <App/>
            </QueryClientProvider>
        </AppContext>
    // </StrictMode>
);

