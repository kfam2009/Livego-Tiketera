-- Estructura de la base de Livego Ticketera para PostgreSQL (Render).
-- Se aplica sola al arrancar si la base está vacía (ver bin/init.php).
CREATE TABLE IF NOT EXISTS cake_sessions (
  id varchar(255) NOT NULL DEFAULT '' PRIMARY KEY,
  data text,
  expires integer
);

CREATE TABLE IF NOT EXISTS events (
  id serial PRIMARY KEY,
  titulo varchar(255),
  descripcion text,
  imagen varchar(255),
  imagen_tipo varchar(50),
  imagen_data text,
  link varchar(255),
  horario text,
  active smallint NOT NULL DEFAULT 1,
  fecha timestamp,
  fechafin timestamp,
  created_at timestamp,
  updated_at timestamp
);

CREATE TABLE IF NOT EXISTS suscriptions (
  id serial PRIMARY KEY,
  event_id integer NOT NULL,
  user_id integer NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_evento_usuario ON suscriptions (event_id, user_id);

CREATE TABLE IF NOT EXISTS users (
  id serial PRIMARY KEY,
  username varchar(100),
  password varchar(255),
  role varchar(20),
  name varchar(255),
  lastname varchar(255),
  dni varchar(255),
  activo smallint NOT NULL DEFAULT 1,
  cakesessionid varchar(255),
  created timestamp,
  modified timestamp
);
CREATE INDEX IF NOT EXISTS idx_username ON users (username);
CREATE INDEX IF NOT EXISTS idx_cakesession ON users (cakesessionid);
