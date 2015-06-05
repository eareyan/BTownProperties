USE b561_f11_48;
DELIMITER $$
DROP FUNCTION IF EXISTS DIST;

CREATE FUNCTION DIST(pid int(11), lid int(11))
RETURNS float
BEGIN
  DECLARE lat1   float;
  DECLARE lng1   float;
  DECLARE lat2   float;
  DECLARE lng2   float;
  DECLARE rho    float;
  DECLARE phi1   float;
  DECLARE phi2   float;
  DECLARE theta1 float;
  DECLARE theta2 float;

  SELECT lat into lat1 FROM Property WHERE propertyId=pid;
  SELECT lng into lng1 FROM Property WHERE propertyId=pid;
  SELECT lat into lat2 FROM Landmark WHERE landmarkId=lid;
  SELECT lng into lng2 FROM Landmark WHERE landmarkId=lid;

  SET rho  = 3963.14;
  SET phi1 = (90.0 - lat1) * PI() / 180.0;
  SET phi2 = (90.0 - lat2) * PI() / 180.0;
  SET theta1 = lng1 * PI() / 180.0;
  SET theta2 = lng2 * PI() / 180.0;

  RETURN rho * ACOS(SIN(phi1) * SIN(phi2) * COS(theta1 - theta2) + COS(phi1) * COS(phi2));
END$$


DELIMITER ;

