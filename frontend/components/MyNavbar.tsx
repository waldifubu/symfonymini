import React, {useState} from "react";
import {
    Collapse,
    DropdownItem,
    DropdownMenu,
    DropdownToggle,
    Nav,
    Navbar,
    NavbarBrand,
    NavbarText,
    NavbarToggler,
    NavItem,
    NavLink,
    UncontrolledDropdown,
} from 'reactstrap';
import {Link, useNavigate} from "react-router-dom";
import Items from "./Items";
import UserContainer from "./UserContainer";
import podcastLogo from "../assets/favicon.png";

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

    function toBirthday() {
        navigate("/birthday");
    }

    function toTours() {
        navigate("/tours");
    }

    return (
        <header>
            <Navbar container="fluid" color="light" className="mb-1" expand="lg">
                <NavbarBrand>
                    <Link style={{color: 'inherit', textDecoration: 'none'}} to="/">
                        <img style={{width: '30px'}} alt="Podcast logo" src={podcastLogo}/> Podcast Management
                    </Link>
                </NavbarBrand>
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
                        <NavItem>
                            <NavLink onClick={toBirthday} style={{cursor: "pointer"}}>
                                Birthday reminder
                            </NavLink>
                        </NavItem>
                        <NavItem>
                            <NavLink onClick={toTours} style={{cursor: "pointer"}}>
                                Tours
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
        </header>
    )
}
export default MyNavbar;
