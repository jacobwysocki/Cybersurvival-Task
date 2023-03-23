import React, { useState, useEffect } from 'react';
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';

function AdminItems(props) {
  const [items, setItems] = useState([]);
  const [itemInput, setItemInput] = useState('');
  const [sequenceNo, setSequenceNo] = useState('');
  const [editingItem, setEditingItem] = useState(null);

  const token = localStorage.getItem('token');

  useEffect(() => {
    fetch("http://localhost:8888/api/items",
            {
                method: 'GET',
                headers: {"Authorization": "Bearer " + token}
            })
      .then((response) => response.json())
      .then((data) => setItems(data))
      .catch((err) => {
        console.log(err.message);
      });
  }, []);


  function addItem() {
    if (itemInput && itemInput.trim() !== '' && sequenceNo) {
      const newItem = {
        itemID: items.length + 1,
        itemString: itemInput,
        sequenceNo: parseInt(sequenceNo),
      };
      const newItems = [...items];
      let sequenceNoExists = false;
  
      if (editingItem == null) {
        for (let i = 0; i < items.length; i++) {
          if (items[i].sequenceNo === newItem.sequenceNo) {
            sequenceNoExists = true;
            break;
          }
        }
        if (!sequenceNoExists) {
          newItems.push(newItem);
          setItems(newItems);
        } else {
          alert('Sequence number already exists. Please enter a unique sequence number.');
        }
      } else {
        for (let i = 0; i < items.length; i++) {
          if (editingItem !== i && items[i].sequenceNo === newItem.sequenceNo) {
            sequenceNoExists = true;
            break;
          }
        }
        if (!sequenceNoExists) {
          newItems[editingItem] = newItem;
          setItems(newItems);
          setEditingItem(null);
        } else {
          alert('Sequence number already exists. Please enter a unique sequence number.');
        }
      }
      setItemInput('');
      setSequenceNo('');
  
      fetch('http://localhost:8888/api/items', {
        method: 'POST',
        body: JSON.stringify({
          itemString: newItem.itemString,
          sequenceNo: newItem.sequenceNo,
        }),
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
      })
        .then((response) => response.json())
        .catch((err) => {
          console.log(err.message);
        });
    }
  }
  
  
  function editItem(itemID) {
    const itemToEdit = items.find(item => item.itemID === itemID);
    if (itemToEdit) {
      const editingIndex = items.indexOf(itemToEdit);
      setItemInput(itemToEdit.itemString);
      setSequenceNo(itemToEdit.sequenceNo);
      setEditingItem(editingIndex);
    }
  }

  

  const deleteItem = (itemID) => {
    
    const filteredItems = items.filter((item) => item.itemID !== itemID);
    setItems(filteredItems);
    console.log(itemID);

    fetch("http://localhost:8888/api/items/" + itemID,
        {
            method: 'DELETE',
            headers: {"Authorization": "Bearer " + token}
        })
        .then(
            (response) => response.json()
        ).then(
        data => setItems(data)
    ).catch((err) => {
        console.log(err.message);
    });

};

    return (
    <div>
      <div>
        <h1>Edit item name</h1>
        <br />
          <div className="formArea">
              <div className="form-container">
                <Form.Control
                  type="text"
                  placeholder="Enter item name"
                  value={itemInput}
                  onChange={(e) => setItemInput(e.target.value)}
                />
              </div>
            </div>

            <h2>Add the sequence number</h2>
            <br />
            <div className="formArea">
              <div className="form-container">
                {<Form.Control
                  type="number"
                  placeholder="Enter sequence number"
                  value={sequenceNo}
                  onChange={(e) => setSequenceNo(e.target.value)}
                /> }
                
            </div>
            </div>
            <Button
              className="button"
              variant="dark"
              type="submit"
              onClick={addItem}
            >
              {editingItem == null ? 'Add Item' : 'Submit changes'}
            </Button>
           
            <br />
            <br />

            {<ul>
             

                {items.map((item, index) => (
                  <li key={item.itemID+ '_' + index}>
                    <p>Item name: {item.itemString}</p>
                    <p>Sequence number: {item.sequenceNo}</p>
                    <Button
                     variant="danger" onClick={() => editItem(item.itemID)}>
                     Edit
                   </Button>
                    <Button variant="danger" onClick={() => deleteItem(item.itemID)}>
                      Delete
                    </Button>
                   
                  </li>
                ))}
              </ul>}
             
            </div>
          </div>
        
  );
}

export default AdminItems;
