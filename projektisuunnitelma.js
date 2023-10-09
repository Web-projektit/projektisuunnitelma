
const url = './confirmation_api.php';

const naytaTulos = response => {
  const ilmoitus = document.querySelector('#ilmoitukset');
  const ilmoitusteksti = document.querySelector('#ilmoitukset p');
  document.querySelector('#ilmoitukset_email').remove();
  if ('OK' in response) {
    ilmoitus.classList.remove('alert-danger');
    ilmoitus.classList.add('alert-success');
    ilmoitusteksti.textContent = response.OK;
    }
  else if ('error' in response) {
    ilmoitusteksti.textContent = response.error;
    }
  else {
    ilmoitusteksti.textContent = 'Tuntematon virhe sähköpostin lähetyksessä.';
    }
  }

document.getElementById('confirmLink').addEventListener('click', () => {
  fetch (url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json',},
      body: JSON.stringify({ email: email }),
      })
  .then (response => {
      if (!response.ok) {
        throw new Error('Virhe lähetettäessä sähköpostia.');
        }
      return response.json();
  })
  .then (data => {
      console.log('Vahvistuslinkin lähetystulos:',data);
      naytaTulos(data);
      })
  .catch(error => {
      console.error('There has been a problem with your fetch operation:', error);
    });
  });



const addInputButtons = document.querySelectorAll(".addInput");
addInputButtons.forEach(button => {
  let id = button.id;  
  button.addEventListener("click", function() {
    // Clone the hidden template
    let template = document.querySelector('#inputTemplate');
    let clone = template.cloneNode(true);
    clone.id = ""; // remove the id to ensure unique ids in the document
    clone.style.display = "list-item"; // make it visible

    // Change the name attribute of the input inside the cloned item
    let inputInClone = clone.querySelector('input');
    inputInClone.setAttribute('name', id+'[]');
    // Add remove functionality
    let removeIcon = clone.querySelector(".remove-icon");
    removeIcon.addEventListener("click", function() {
      clone.remove();
    });

    // Append the cloned item to the respective checkbox list
    button.nextElementSibling.appendChild(clone);
  });
});

document.querySelectorAll(".openChat").forEach(button => {
    button.addEventListener("click", () => {
      let chatBox = button.parentElement.querySelector(".chatBox");
      chatBox.hidden = !chatBox.hidden;
    });
  });

document.querySelectorAll(".commentInput").forEach(input => {
    input.addEventListener("keypress", async event => {
      if (event.key === "Enter" && input.value.trim() !== "") {
        event.preventDefault();
        if (!input.validity.valid) {
          input.classList.add("is-invalid");
          input.nextElementSibling.textContent = input.validationMessage; 
          // input.reportValidity();
          return;
          } 
        // Get the name of the input field
        // let inputField = lastCommentDisplay.previousElementSibling.previousElementSibling;
        console.log("kommentin id:",input.id);    
        // Save to database (as previously discussed)
        try {
            let response = await fetch('kasittelija_kommentti.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    },
                body: `project_id=${projekti_id}&field=${input.id}&comment=${input.value}`,
                });
            let result = await response.json();
            console.log('result:', JSON.stringify(result))
            if (result !== "OK") {
                input.classList.add("is-invalid");
                input.nextElementSibling.textContent = result; 
                }
            else {
              let commentBox = input.previousElementSibling;
              let comment = document.createElement("div");
              comment.innerHTML = `<strong>${username}:</strong> ${input.value}`;
              commentBox.appendChild(comment);
              // Update the last comment display
              let lastComment = input.parentElement.previousElementSibling;
              lastComment.textContent = input.value;
              lastComment.removeAttribute('hidden');
              }  
            } 
        catch (error) {
            console.error('Error:', error);
            }  
        input.value = ""; 
      }
    });
  });
  