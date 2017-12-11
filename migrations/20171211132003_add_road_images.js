

var schema = require("../node_utils/knexschema").default;

exports.up = function(knex, Promise) {
    // SELECT concat('table.',data_type,'("',column_name,'");') FROM information_schema.columns WHERE 
    // table_name = 'light_report' ;
    
  return Promise.all([
      schema.createTable(knex,"road_images",(table)=>{
        table.text("url");
        table.text("modified_url");
        table.text("path");
        table.text("lat");
        table.text("lng");

        table.bigInteger("report_id");
        
      })
    ]);
};

exports.down = function(knex, Promise) {
  
};
