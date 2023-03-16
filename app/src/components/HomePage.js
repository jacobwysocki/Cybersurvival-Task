import '../App.css';
import React from 'react';
import {Link} from "react-router-dom";
import Button from 'react-bootstrap/Button';
/**
 * HomePage.
 * 
 * 
 */

function HomePage() {


    return (
        <div>
            <h1>Homepage</h1>
        <Button as={Link} to="/login" className="button"
          variant="dark"
          type="submit">
          Log in
        </Button>
        
        <Button as={Link} to="/register" className="button"
          variant="dark"
          type="submit">
          Register
        </Button>

        <Button as={Link} to="/individual-stage" className="button"
          variant="dark"
          type="submit">
          Individual Stage
        </Button>
        </div>
    )
}

export default HomePage;