import './App.css';

import {Routes, Route} from 'react-router-dom';

import React, {useState, useEffect} from 'react';

import HomePage from './components/HomePage';
import Login from "./components/Login";
import Register from "./components/Register";
import IndividualStage from './components/IndividualStage.js';
import AdminItems from './components/AdminItems.js';
import SecInfoPage from "./components/SecInfo";
function App() {


  const [authenticated, setAuthenticated] = useState(false);
  const handleAuthenticated = (isAuthenticated) => {
    setAuthenticated(isAuthenticated)
  }

  return (
    <div className="App">
      <header className="App-header">
        
        <Routes>
          <Route path="/" element={<HomePage/>}/>
          <Route path="/login" element={<Login handleAuthenticated={setAuthenticated} authenticated={authenticated}/>}/>
          <Route path="/register" element={<Register handleAuthenticated={setAuthenticated}/>}/>
          {/* <Route path="*" element={<p>Not Found</p>}/> */}
          <Route path="/individual-stage" element={<IndividualStage/>}/>
          <Route path="/AdminItems" element={<AdminItems/>}/>
          <Route path="/SecInfo" element={<SecInfoPage/>}/>
        </Routes>
      </header>
    </div>
  );
}

export default App;
