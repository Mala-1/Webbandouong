// Roles
Table roles {
  role_id int [pk, increment]
  name varchar
}

// Users
Table users {
  user_id int [pk, increment]
  username varchar
  password varchar
  email varchar
  phone varchar
  address varchar
  role_id int [ref: > roles.role_id]
}

// Permissions
Table permissions {
  permission_id int [pk, increment]
  name varchar
}

// Role-Permission Details
Table role_permission_details {
  role_permission_detail_id int [pk, increment]
  role_id int [ref: > roles.role_id]
  permission_id int [ref: > permissions.permission_id]
  action varchar
}

// Categories
Table categories {
  category_id int [pk, increment]
  name varchar
}

// Brands
Table brand {
  brand_id int [pk, increment]
  name varchar
  image varchar
}

// Products
Table products {
  product_id int [pk, increment]
  name varchar
  description text
  price decimal
  category_id int [ref: > categories.category_id]
  size varchar
  brand_id int [ref: > brand.brand_id]
  origin varchar
}

// Packaging Options
Table packaging_options {
  packaging_option_id int [pk, increment]
  product_id int [ref: > products.product_id]
  packaging_type varchar
  stock int
  image varchar
  price decimal
  unit_quantity varchar
}

// Product Images
Table product_images {
  product_id int [ref: > products.product_id]
  image varchar
}

// Orders
Table orders {
  order_id int [pk, increment]
  user_id int [ref: > users.user_id]
  status varchar
  total_price decimal
  shipping_address varchar
  note text
  created_at datetime
  payment_method_id int [ref: > payment_method.payment_method_id]
}

// Payment Methods
Table payment_method {
  payment_method_id int [pk, increment]
  name varchar
}

// Order Details
Table order_details {
  order_detail_id int [pk, increment]
  order_id int [ref: > orders.order_id]
  product_id int [ref: > products.product_id]
  packaging_option_id int [ref: > packaging_options.packaging_option_id]
  quantity int
  price decimal
}

// Supplier
Table supplier {
  supplier_id int [pk, increment]
  name varchar
  email varchar
  address varchar
}

// Import Order
Table import_order {
  import_order_id int [pk, increment]
  supplier_id int [ref: > supplier.supplier_id]
  user_id int [ref: > users.user_id]
  total_price decimal
  created_at datetime
}

// Import Order Details
Table import_order_details {
  impor_order_detail_id int [pk, increment]
  import_order_id int [ref: > import_order.import_order_id]
  product_id int [ref: > products.product_id]
  quantity int
  price decimal
  total_price decimal
  packaging_option_id int [ref: > packaging_options.packaging_option_id]
}
