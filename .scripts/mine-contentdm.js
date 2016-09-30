const fs   = require('fs');
const http = require('http');
const path = require('path');

const options = {
  host: 'digital.tcl.sc.edu',
  port: '81',
  path: '/dmwebservices/?q=dmQuery/hsn/CISOSEARCHALL^*^any/contri!covera!date!descri!publis!relati!subjec!title/0/1024/0/0/0/0/0/1/json',
  headers: {
    'Content-Type': 'application/json'
  }
};

let data = '';

http.get(options, (result) => {
  result.on('data', (chunk) => {
    data += chunk.toString();

    console.log('Grabbing chunk...');
  });

  result.on('end', () => {
    try {
      data = JSON.parse(data);
    } catch (error) {
      console.log('Error parsing data in JSON format.');

      throw error;
    }

    fs.writeFile(path.resolve(__dirname, '../private/contentdm-data.json'), JSON.stringify(data.records), (error) => {
      if (error) {
        console.log('Error writing data.');

        throw error;
      }

      console.log('Data has been written.');
    });
  });
}).on('error', (error) => {
  console.log('Received Error:', error);
});
