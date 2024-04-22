/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import axios from "axios";
import {v4 as uuidv4} from 'uuid'
import './styles/app.css';

const processDocButton = document.querySelector('[data-button="process-doc"]')
if (processDocButton) {
    const messageHolder = document.querySelector('[data-message="uuid"]')
    const url = new URL('http://localhost:9999/.well-known/mercure')
    url.searchParams.append('topic', '/api/documents/{id}')
    const eventSource = new EventSource(url)
    eventSource.onmessage = (event) => {
        const data = JSON.parse(event.data)
        messageHolder.innerHTML = `server send event : The document with id ${data.id} has been processed.`
    }
    window.onbeforeunload = function () {
        eventSource.close();
    };
    processDocButton.addEventListener(
        'click',
        (e) => {
            e.preventDefault()
            messageHolder.innerHTML = 'Wait for processing...'
            let to = setTimeout(() => {
                const uuid = uuidv4()
                axios.post(
                    '/api/documents',
                    {id: uuid}
                ).then((response) => {
                    messageHolder.innerHTML = `The document with id ${uuid} has been sent for processing by the worker in queue.`
                }).finally(
                    () => {
                        clearTimeout(to)
                    }
                )
            }, 2000)
        }
    )
}


