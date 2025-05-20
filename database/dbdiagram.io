Table brand {
  brand_id int [pk]
  name varchar(100)
  image varchar(255)
  is_deleted boolean
}

Table cart {
  cart_id int [pk]
  user_id int [ref: > users.user_id]
  created_at datetime
}

Table cart_details {
  cart_detail_id int [pk]
  cart_id int [ref: > cart.cart_id]
  packaging_option_id int [ref: > packaging_options.packaging_option_id]
  quantity int
  price decimal(30,2)
  total_price decimal(30,2)
}

Table categories {
  category_id int [pk]
  name varchar(100)
  image varchar(255)
  is_deleted boolean
}

Table import_order {
  import_order_id int [pk]
  supplier_id int [ref: > supplier.supplier_id]
  user_id int [ref: > users.user_id]
  total_price decimal(30,2)
  created_at datetime
  status varchar(50)
}

Table import_order_details {
  import_order_detail_id int [pk]
  import_order_id int [ref: > import_order.import_order_id]
  product_id int [ref: > products.product_id]
  quantity int
  price decimal(30,2)
  total_price decimal(30,2)
  packaging_option_id int [ref: > packaging_options.packaging_option_id]
}

Table orders {
  order_id int [pk]
  user_id int [ref: > users.user_id]
  status varchar(50)
  total_price decimal(30,2)
  shipping_address text
  note text
  created_at datetime
  payment_method_id int [ref: > payment_method.payment_method_id]
}

Table order_details {
  order_detail_id int [pk]
  order_id int [ref: > orders.order_id]
  product_id int [ref: > products.product_id]
  packaging_option_id int [ref: > packaging_options.packaging_option_id]
  quantity int
  price decimal(25,2)
}

Table packaging_options {
  packaging_option_id int [pk]
  product_id int [ref: > products.product_id]
  packaging_type varchar(100)
  stock int
  image varchar(255)
  price int
  unit_quantity varchar(50)
  is_deleted boolean
}

Table payment_method {
  payment_method_id int [pk]
  name varchar(100)
}

Table permissions {
  permission_id int [pk]
  name varchar(100)
}

Table products {
  product_id int [pk]
  name varchar(100)
  description text
  price int
  category_id int [ref: > categories.category_id]
  size varchar(50)
  brand_id int [ref: > brand.brand_id]
  origin varchar(100)
  is_deleted boolean
}

Table product_images {
  image_id int [pk]
  product_id int [ref: > products.product_id]
  image varchar(255)
}

Table profitmargin {
  margin_percent float
}

Table roles {
  role_id int [pk]
  name varchar(100)
}

Table role_permission_details {
  role_permission_detail_id int [pk]
  role_id int [ref: > roles.role_id]
  permission_id int [ref: > permissions.permission_id]
  action varchar(50)
}

Table supplier {
  supplier_id int [pk]
  name varchar(100)
  email varchar(100)
  address text
  is_deleted boolean
}

Table users {
  user_id int [pk]
  username varchar(100)
  password varchar(255)
  email varchar(100)
  phone varchar(20)
  address text
  role_id int [ref: > roles.role_id]
}
