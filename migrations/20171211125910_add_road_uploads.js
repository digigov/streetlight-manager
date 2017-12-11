

var schema = require("../node_utils/knexschema").default;

exports.up = function(knex, Promise) {
    // SELECT concat('table.',data_type,'("',column_name,'");') FROM information_schema.columns WHERE 
    // table_name = 'light_report' ;
    
  return Promise.all([
      schema.createTable(knex,"road_reports",(table)=>{
        table.text("url");
        table.text("modified_url");
        table.text("images");

        table.bigInteger("town_id");
        table.bigInteger("status");
        table.text("name");
        table.text("contact");
        table.text("comment");
        table.text("admin_comment");
        table.text("email");
        table.text("location");
        table.text("lat");
        table.text("lng");
        
      })
    ]);
};

exports.down = function(knex, Promise) {
  
};
