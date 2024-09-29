import {FC, useContext} from "react";
import {Button} from "reactstrap";
import AppContext, {useGlobalContext} from "./GlobalContext";

const UserContrainer: FC = () => {
    const {name, setName} = useGlobalContext();

    const logout = () => {
        setName(null)
    }

    return (
        <span>
            {name ? (
                <>
                    <small>Hello, {name}</small>
                    <Button onClick={logout} color="outline-info">Logout</Button>
                </>
            ) : (
                <p>Please login</p>
            )}
        </span>
    )
}

export default UserContrainer;