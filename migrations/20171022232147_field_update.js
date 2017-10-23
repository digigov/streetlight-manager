

var schema = require("../node_utils/knexschema").default;

exports.up = function(knex, Promise) {
    // SELECT concat('table.',data_type,'("',column_name,'");') FROM information_schema.columns WHERE 
    // table_name = 'light_report' ;
    
  return Promise.all([
      schema.table(knex,"streetlights",(table)=>{
        table.text("powerloc");
        table.text("led_device_no");
        table.text("powers");
        table.text("precision");
      })
    ]);
};

exports.down = function(knex, Promise) {
  
};
