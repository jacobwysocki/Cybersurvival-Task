import React, {useState, useEffect} from "react";
import {DragDropContext, Droppable, Draggable} from 'react-beautiful-dnd';
import '../App.css';
import ReactDOM from 'react-dom';
import Countdown from 'react-countdown';

function IndividualStage(){
  const [items, setItems] = useState([]);

  useEffect( () => {
      fetch("http://127.0.0.1:8080/api/items")
      .then( 
          (response) => response.json() 
      ).then(
          data => setItems(data)
      ).catch((err) => {
          console.log(err.message);
      });
  }, []);
    
  const TableOfItems = (props) =>{
  
    function handleOnDragEnd(result) {
      if (!result.destination) return;
  
      const [reorderedItem] = props.items.splice(result.source.index, 1);
      props.items.splice(result.destination.index, 0, reorderedItem);
  
      props.setItems(props.items);
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
    const websocket = new WebSocket("ws://localhost:8082");
    websocket.onopen = function (e){console.log("connection established")}
    websocket.onmessage = function (e){
      let message = JSON.parse(e.data);
      let command = message.command;

      switch(command){
        case "startIndividual":
          ReactDOM.render(countdown, document.getElementById('indvTask'));
          break;
      }
    }
  });

  // Renderer callback with condition
  const renderer = ({minutes, seconds, completed }) => {
    if (completed) {
      // Render a completed state
      fetch("http://127.0.0.1:8080/api/individualResults",{
        method: 'POST',
        body: JSON.stringify({experimentsID: 1, userID: 1, rankingOrder: items})
      })
      return <h1>Thank you participating. The Group stage will begin shortly</h1>
    } else {
      // Render a countdown
      return (<div>
                <h1>{minutes}:{seconds}</h1>
                <TableOfItems items={items} setItems={setItems} />
            </div>);
    }
  };

  const countdown = <Countdown
                      date={Date.now() + 15000}
                      renderer={renderer}
                    />;

  // return <h1>Waiting for the signal to start!</h1>;

  return <div id="indvTask">
          <h1>Waiting for the Individual Stage to begin</h1>
        </div>;
}

export default IndividualStage;