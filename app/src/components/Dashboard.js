import Login from "./Login";
import React, {useState, useEffect} from 'react';
import { Navigate } from "react-router-dom";
import Button from "react-bootstrap/Button";
import { Link } from "react-router-dom";
import GroupSelect from "./GroupSelect";


function Dashboard(props) {

    useEffect(
        () => {
            if (localStorage.getItem('token')) {
                props.handleAuthenticated(true)
            }
        }
        , [])


    const handleSignOut = () => {
        props.handleAuthenticated(false)
        localStorage.removeItem('token')
    }

    const handleClick = (path) => {
        return <Navigate replace to={path} />;
    }


    return(
    <div>
        {props.authenticated &&
            <div>
            <p>Welcome to your Dashboard</p>
                <Button className="buttonSignOut"
                        variant="dark"
                        type="submit"
                        onClick={handleSignOut}>
                    Sign out
                </Button>
                {localStorage.getItem('rank') == "admin" &&
                    <Button as={Link} to="/AdminUsers" className="button"
                    variant="dark"
                    type="submit">
                        Manage Users
                    </Button>}
                <div>
                    <Button as={Link} to="/startExperiment" className="button"
                    variant="dark"
                    type="submit">
                    Start the cybersurvival experiment!
                    </Button>
                </div>
            </div>

        }
        {!props.authenticated &&
            <Navigate replace to="/login"/>
        }
    </div>
)

}

export default Dashboard;
