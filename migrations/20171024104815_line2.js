

var schema = require("../node_utils/knexschema").default;

exports.up = function(knex, Promise) {
    // SELECT concat('table.',data_type,'("',column_name,'");') FROM information_schema.columns WHERE 
    // table_name = 'light_report' ;
    
  return Promise.all([
      schema.table(knex,"account",(table)=>{
        table.text("line_led_access_token");
      })
    ]);
};

exports.down = function(knex, Promise) {
  
};
