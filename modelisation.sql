CREATE TABLE "agents" (
  "agent_id" INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "name" varchar,
  "password" varchar
);

CREATE TABLE "canaux" (
  "canaux_id" INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "nom" varchar
);

CREATE TABLE "clients" (
  "client_id" INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "nom" varchar,
  "email" varchar,
  "telephone" varchar
);

CREATE TABLE "conversations" (
  "conversation_id" INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "premier_messag_id" int,
  "agent_id" int,
  "client_id" int,
  "debut" datetime,
  "fin" datetime
);

CREATE TABLE "messages" (
  "message_id" INT GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "conversation_id" int,
  "channel_id" int,
  "client_id" int,
  "date_envoi" datetime,
  "contenu" text,
  "type" enum(entrant,sortant)
);

ALTER TABLE "conversations" ADD FOREIGN KEY ("premier_messag_id") REFERENCES "messages" ("message_id");

ALTER TABLE "conversations" ADD FOREIGN KEY ("agent_id") REFERENCES "agents" ("agent_id");

ALTER TABLE "conversations" ADD FOREIGN KEY ("client_id") REFERENCES "clients" ("client_id");

ALTER TABLE "messages" ADD FOREIGN KEY ("conversation_id") REFERENCES "conversations" ("conversation_id");

ALTER TABLE "messages" ADD FOREIGN KEY ("channel_id") REFERENCES "canaux" ("canaux_id");

ALTER TABLE "messages" ADD FOREIGN KEY ("client_id") REFERENCES "clients" ("client_id");
