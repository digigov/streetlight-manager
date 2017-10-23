
module.exports.default = {
    bigInteger(table,name){
        return table.bigInteger(name);
    },
    bigIntegers(table,columns){
        return columns.map(c=> table.bigInteger(c));
    },
    texts(table,columns){
        columns.forEach(c=>{
            table.text(c)
        });
    },
    timestamps(knex,table){
        table.timestamp('created_at').defaultTo(knex.fn.now());
        table.timestamp('updated_at').defaultTo(knex.fn.now());
        table.timestamp('deleted_at');
    },
    createTable(knex,table,cb){
        return knex.schema.createTable(table, (table)=>{
            table.bigIncrements();
            this.timestamps(knex,table);
            table.bigInteger("cuid");
            table.bigInteger("muid");

            return cb(table);
        });
    },
    table(knex,table,cb){
        return knex.schema.table(table, cb);
    }
}