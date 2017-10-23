

var schema = require("../node_utils/knexschema").default;

exports.up = function(knex, Promise) {
    // SELECT concat('table.',data_type,'("',column_name,'");') FROM information_schema.columns WHERE 
    // table_name = 'light_report' ;
    
  return Promise.all([
      schema.createTable(knex,"account",(table)=>{
        table.text("acc");
        table.text("pwd");
        table.text("city");
      }),
      schema.createTable(knex,"light_change_log",(table)=>{
        table.text("lat");
        table.text("lng");
        table.text("old_lat");
        table.text("old_lng");
        table.bigInteger("light_id");
      }),
      schema.createTable(knex,"light_log",(table)=>{
        table.text("name");
        table.text("text");
        table.bigInteger("light_id");
      }),
      schema.createTable(knex,"light_report",(table)=>{
        table.bigint("light_id");
        table.text("name");
        table.text("images");
        table.text("contact");
        table.bigInteger("status");
        table.text("comment");
        table.text("admin_comment");
        table.text("email");
        table.text("led_no");
      }),
      schema.createTable(knex,"streetlights",(table)=>{
        table.text("name");
        table.bigInteger("town_id");
        table.text("type");
        table.text("lng");
        table.text("lat");
        table.text("height");
        table.text("status");
        table.text("led_no");
      }),
      schema.createTable(knex,'town',(table)=>{
        table.text("name");
        table.text("county");
        table.text("city");
      })
    ]);
};

exports.down = function(knex, Promise) {
  
};
