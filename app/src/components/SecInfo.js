import '../App.css';
import React from "react";
// import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
// import { faKey, faMobileAlt, faWifi, faSignOutAlt } from "@fontawesome/free-solid-svg-icons";

/**
 * SecInfo.
 * This component is used to display the security information of  the application.
 *
 */
function SecInfoPage(){

    return (
        <div className="container mt-5">
            <h1 className="text-center mb-5">Security Information</h1>
            <div>
                <p> When using a web app, it is essential to
                    follow security best practices to protect your personal information and ensure a
                    safe online experience.
                    Here are some key security tips to keep in mind</p>
            </div>
            <div>
                <h2> Use Strong Passwords</h2>
                <p>
                    Create unique and strong passwords for each of your accounts. Use a
                    combination of letters, numbers, and symbols to make it difficult for
                    others to guess. Avoid using easily guessable information, such as
                    birthdays, family names, or favorite sports teams.
                </p>
            </div>
            <div>
                <h2>Enable Two-Factor Authentication</h2>
                <p>
                    Turn on two-factor authentication (2FA) for an extra layer of
                    security. This requires you to verify your identity using a
                    secondary device, such as your phone, when logging in. Many services
                    offer 2FA through text messages, authenticator apps, or security
                    keys.
                </p>
            </div>
            <div>
                <h2>Be Cautious with Public Wi-Fi</h2>
                <p>
                    Avoid using public Wi-Fi networks, especially when accessing
                    sensitive information. Public Wi-Fi networks can be easily
                    compromised, putting your data at risk. If you must use public Wi-Fi,
                    consider using a virtual private network (VPN) to encrypt your
                    connection and protect your data.
                </p>
            </div>
            <div>
                <h2> Log out after use</h2>
                <p>
                    Always log out of your accounts when you are finished,
                    especially on shared or public devices.
                </p>
            </div>
        </div>
    );
}

export default SecInfoPage;
