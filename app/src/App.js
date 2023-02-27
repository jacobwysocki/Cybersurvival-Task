import './App.css';

import {Routes, Route} from 'react-router-dom';

import React, {useState, useEffect} from 'react';

import HomePage from './components/HomePage';
import Login from "./components/Login";

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
          <Route path="/login" element={<Login handleAuthenticated={setAuthenticated}/>}/>
          <Route path="*" element={<p>Not Found</p>}/>
        </Routes>
      </header>
    </div>
  );
}

export default App;
