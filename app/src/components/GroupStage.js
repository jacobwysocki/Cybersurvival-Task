import React, {useState, useEffect} from "react";
import {DragDropContext, Droppable, Draggable} from 'react-beautiful-dnd';
import '../App.css';
import ReactDOM from 'react-dom';
import Countdown from 'react-countdown';
import {Navigate} from 'react-router-dom';

function GroupStage(){
  const [items, setItems] = useState([]);
  const [started, setStarted] = useState(false);

      const websocket = new WebSocket("ws://localhost:8082?groupID=" + localStorage.getItem('groupID'));

  // useEffect( () => {
  //     fetch("http://127.0.0.1:8080/api/items",{
  //       headers: {
  //         "Authorization" : "Bearer " + localStorage.getItem('token')
  //       }
  //     })
  //     .then( 
  //         (response) => response.json() 
  //     ).then(
  //         data => setItems(data)
  //     ).catch((err) => {
  //         console.log(err.message);
  //     });
  // }, []);
    
  const TableOfItems = (props) =>{
  
    function handleOnDragEnd(result) {
      if (!result.destination) return;
  
      const [reorderedItem] = props.items.splice(result.source.index, 1);
      props.items.splice(result.destination.index, 0, reorderedItem);
  
      props.setItems(props.items);

      websocket.send(JSON.stringify({
        "userRank" : "user",
        "itemsUpdate" : props.items
      }))

      console.log(props.items);
    }

       return <DragDropContext onDragEnd={handleOnDragEnd}>
          <Droppable droppableId="items">
            {(provided) => (
              <ul className="itemsList" {...provided.droppableProps} ref={provided.innerRef}>
                {props.items.map((item, index) => {
                  return (
                    <Draggable key={item.itemString} draggableId={item.itemString} index={index}>
                      {(provided) => (
                        <li ref={provided.innerRef} {...provided.draggableProps} {...provided.dragHandleProps}>
                          <p>{item.itemString}</p>
                        </li>
                      )}
                    </Draggable>
                  );
                })}
                {provided.placeholder}
              </ul>
            )}
          </Droppable>
        </DragDropContext>
      ;
    }

  useEffect(() => {
    websocket.onopen = function (e){console.log("connection established")}
    websocket.onmessage = function (e){
      let message = JSON.parse(e.data);
      console.log(message);

      let command = message.command;
      switch(command){
        case "startGroup":
          setItems(message.items);
          ReactDOM.render(countdown, document.getElementById('indvTask'));
          break;
      }

      let user = message.userRank;
      if(user === "user"){
        // console.log(j);
        setItems(message.itemsUpdate);
      }
    }
  });

  // Renderer callback with condition
  const renderer = ({minutes, seconds, completed }) => {
    if (completed) {
    //   // Render a completed state
    //   fetch("http://127.0.0.1:8080/api/individualResults",{
    //     method: 'POST',
    //     body: JSON.stringify({experimentsID: 1, userID: 1, rankingOrder: items}),
    //     headers:{
    //       "Authorization" : "Bearer " + localStorage.getItem("token")
    //     }
    //   })
      return <div>
              {/* <Navigate to="/groupStage"/> */}
              <h1>The End</h1>
            </div>
    } else {
      setStarted(true);
      // Render a countdown
      return (<div>
                <h1>{minutes}:{seconds}</h1>
            </div>);
    }
  };

  const countdown = <Countdown
                      date={Date.now() + 1000 * 60}
                      renderer={renderer}
                    />;

  return <div>
            <div id="indvTask">
              <h1>Waiting for the Group Stage to begin</h1>
            </div>
            <div>
              {started && 
              
              <TableOfItems items={items} setItems={setItems} />}
            </div>
        </div>;
}

export default GroupStage;