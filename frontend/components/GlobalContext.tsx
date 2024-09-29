import React, {createContext, FC, useContext, useState} from "react";

// Creates context with value and its setter, default value is required
const GlobalContext: React.Context<any> = createContext({
    name: '',
    setName: () => {}
})

// Creates hook which injects context
export const useGlobalContext = () => useContext(GlobalContext);

// AppContext is provider with state, to provide the context value as state
// @ts-ignore
const AppContext = ({children}) => {
    const [name, setName]: [string, (value: (((prevState: string) => string) | string)) => void] = useState('Waldi')

    return <GlobalContext.Provider value={{name: name, setName: setName}}>{children}</GlobalContext.Provider>
}

export default AppContext;