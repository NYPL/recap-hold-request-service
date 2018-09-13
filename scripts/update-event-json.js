/**
 * Simple script for building an event.json suitable for testing the deployed lambda.
 *
 * Updates ./event.json with the POST body in ./sample-post.json
 *
 * Usage:
 *
 *   # Update event.json:
 *   node scripts/update-event-json
 *   # Copy result:
 *   pbcopy < event.json
 *
 *   # Next, use the contents of your clipboard to configure and run an event
 *   # against the deployed lambda.
 */

const fs = require('fs')

const eventJson = require('../event.json')
const sampleEvent = require('../sample-post.json')

eventJson.body = JSON.stringify(sampleEvent)

console.log('Updating ./event.json with sample body in ./sample-post.json')
fs.writeFileSync('./event.json', JSON.stringify(eventJson, null, 2))
