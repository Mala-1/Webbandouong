Table brand {
  brand_id int [pk]
  name varchar(100)
  image varchar(255)
  is_deleted boolean
}

Table cart {
  cart_id int [pk]
  user_id int
  created_at datetime
}

Table cart_details {
  cart_detail_id int [pk]
  cart_id int
  packaging_option_id int
  quantity int
  price decimal(10)
  total_price decimal(10)
}

Table categories {
  category_id int [pk]
  name varchar(100)
  image varchar(255)
  is_deleted boolean
}

Table import_order {
  import_order_id int [pk]
  supplier_id int
  user_id int
  total_price decimal(20)
  created_at datetime
}

Table import_order_details {
  import_order_detail_id int [pk]
  import_order_id int
  product_id int
  quantity int
  price decimal(10)
  total_price decimal(10)
  packaging_option_id int
}

Table orders {
  order_id int [pk]
  user_id int
  status varchar(50)
  total_price decimal(10)
  shipping_address text
  note text
  created_at datetime
  payment_method_id int
}

Table order_details {
  order_detail_id int [pk]
  order_id int
  product_id int
  packaging_option_id int
  quantity int
  price decimal(10)
}

Table packaging_options {
  packaging_option_id int [pk]
  product_id int
  packaging_type varchar(100)
  stock int
  image varchar(255)
  price int
  unit_quantity varchar(50)
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
  category_id int
  size varchar(50)
  brand_id int
  origin varchar(100)
  is_deleted boolean
}

Table product_images {
  image_id int [pk]
  product_id int
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
  role_id int
  permission_id int
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
  role_id int
}

Ref: cart.user_id > users.user_id
Ref: cart_details.cart_id > cart.cart_id
Ref: cart_details.packaging_option_id > packaging_options.packaging_option_id

Ref: import_order.supplier_id > supplier.supplier_id
Ref: import_order.user_id > users.user_id
Ref: import_order_details.import_order_id > import_order.import_order_id
Ref: import_order_details.product_id > products.product_id
Ref: import_order_details.packaging_option_id > packaging_options.packaging_option_id

Ref: order_details.order_id > orders.order_id
Ref: order_details.product_id > products.product_id
Ref: order_details.packaging_option_id > packaging_options.packaging_option_id
Ref: orders.user_id > users.user_id
Ref: orders.payment_method_id > payment_method.payment_method_id

Ref: packaging_options.product_id > products.product_id
Ref: products.category_id > categories.category_id
Ref: products.brand_id > brand.brand_id
Ref: product_images.product_id > products.product_id

Ref: users.role_id > roles.role_id
Ref: role_permission_details.role_id > roles.role_id
Ref: role_permission_details.permission_id > permissions.permission_id
