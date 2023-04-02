import '../App.css';
import React from "react";

/**
 * information on the database for developers
 * @Author Jack Hunter
 */
function DbInfoPage(){

    return (
        <div className="container mt-5">
            <h1 className="text-center mb-5">Database Structure</h1>
            <div>
                <p> If you're a developer or admin, here's the database structure to be mindful of and what the tables do: </p>
            </div>
            <div>
                <h2>Experiments</h2>
                <p>
                    Used to create a log of each experiment that an admin or assessing body may perform.
                    Contains information such as the date it was conducted, observations, and whether or not
                    the experiment is ongoing.
                </p>
            </div>
            <div>
                <h2>Groups, UserGroups and GroupResults</h2>
                <p>
                    Allows an admin to create a group, as well as log results posted by the group. Links to the UserGroups
                    table to assign individuals to a group. Records results of the group segment of the experiment.
                </p>
            </div>
            <div>
                <h2>Individual Results</h2>
                <p>
                    Logs individual results during the individual stage of the experiment.
                </p>
            </div>
            <div>
                <h2>Items</h2>
                <p>
                    Contains a list of all items that will be used in the cybersurvival experiment. Can be edited and
                    added to by admins running the experiment.
                </p>
                <h2>Users and UserRanks</h2>
                <p>
                    Contains information about users in the system. Used for authentication as well as tracking across
                    experiments. Contains a rank addition from the UserRanks table to specify whether user is generic or an admin.
                </p>
            </div>
        </div>
    );
}

export default DbInfoPage;
