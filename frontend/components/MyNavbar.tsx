import React, {createContext, FC, useState} from "react";
import {
    Collapse,
    Navbar,
    NavbarToggler,
    NavbarBrand,
    Nav,
    NavItem,
    NavLink,
    UncontrolledDropdown,
    DropdownToggle,
    DropdownMenu,
    DropdownItem,
    NavbarText, Button,
} from 'reactstrap';
import Items from "./Items";
import UserContainer from "./UserContainer";
import {Link} from "react-router-dom";
import {redirect} from "react-router-dom";
import {useNavigate} from "react-router-dom";

const MyNavbar = () => {
    const [isOpen, setIsOpen] = useState(false);
    const toggle = () => setIsOpen(!isOpen);
    const navigate = useNavigate();

    function toPeople() {
        navigate("/people");
    }

    function toPeopleOk() {
        navigate("/people2");
    }

    return (
        <Navbar container="fluid" color="light" className="mb-1" expand="lg">
            <NavbarBrand href="/">Podcast Management</NavbarBrand>
            <NavbarToggler onClick={toggle}/>
            <Collapse isOpen={isOpen} navbar>
                <Nav className="me-auto" navbar>
                    <NavItem>
                        <NavLink onClick={toPeople} style={{cursor: "pointer"}}>
                            People broken
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink onClick={toPeopleOk} style={{cursor: "pointer"}}>
                            People ok
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink href="https://github.com/reactstrap/reactstrap">
                            GitHub
                        </NavLink>
                    </NavItem>
                    <UncontrolledDropdown nav inNavbar>
                        <DropdownToggle nav caret>
                            Options
                        </DropdownToggle>
                        <DropdownMenu end>
                            <DropdownItem>Option 1</DropdownItem>
                            <DropdownItem>Option 2</DropdownItem>
                            <DropdownItem divider/>
                            <DropdownItem>Reset</DropdownItem>
                        </DropdownMenu>
                    </UncontrolledDropdown>
                </Nav>
                <NavbarText>
                    <Items/>
                </NavbarText>
                &nbsp;
                <NavbarText>
                    <UserContainer/>
                </NavbarText>
            </Collapse>
        </Navbar>
    )
}
export default MyNavbar;