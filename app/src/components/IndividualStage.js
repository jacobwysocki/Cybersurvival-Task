import React, {useState, useEffect} from "react";
import {shuffle} from 'fast-shuffle';
import {DragDropContext, Droppable, Draggable} from 'react-beautiful-dnd';
import '../App.css';

function IndividualStage(){
    const [items, setItems] = useState([]);

    useEffect( () => {
        fetch("http://127.0.0.1:8080/api/items")
        .then( 
            (response) => response.json() 
        ).then(
            data => shuffle(data)
        ).then(
            shuffledData => setItems(shuffledData)
        ).catch((err) => {
            console.log(err.message);
        });
    }, []);

    function handleOnDragEnd(result) {
        if (!result.destination) return;
    
        const [reorderedItem] = items.splice(result.source.index, 1);
        items.splice(result.destination.index, 0, reorderedItem);
    
        setItems(items);
        console.log(items);
      }
    
      return (
            <DragDropContext onDragEnd={handleOnDragEnd}>
              <Droppable droppableId="items">
                {(provided) => (
                  <ul className="itemsList" {...provided.droppableProps} ref={provided.innerRef}>
                    {items.map((item, index) => {
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
      );
}

export default IndividualStage;