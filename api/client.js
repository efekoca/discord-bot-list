const Discord = require("discord.js");
const moment = require("moment");
require("moment-duration-format");
const express = require('express');
let app = express();
 
const client = new Discord.Client();
app.get("/", async (req, res) => {
  if(isNaN(req.query.id)) return res.json({"user": 0});
  if(req.query.id > 9223372036854775807) return res.json({"user": 0});
  try{
    let user = await client.users.fetch(req.query.id);
    let userFlags = user.flags.toArray();
    let presence = user.presence;
    res.json({"flags": userFlags, "user": user, "presence": presence});     
  }catch{
    res.json({"user": 0});
  }
}).listen(process.env.PORT);

client.login(""); // Token for the bot.
client.on('ready', () => {
  console.log("Successfully logged in!");
});