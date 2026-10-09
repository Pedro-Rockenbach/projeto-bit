import sys

import mariadb


def conn():
    try:
        conn = mariadb.connect(
            user="root",
            password="",
            host="127.0.0.1",
            port=3306,
            database="ub",
        )

        print("\nConexão com banco bem sucedida\n")

    except mariadb.Error as e:
        print(f"Erro conectando ao MariaDB: {e}")
        raise SystemExit(1)
    return conn

def insert(conn, names):
    try:
        cur = conn.cursor()

        for name in names:
            try:
                cur.execute(
                    """
                    INSERT IGNORE INTO subjects (name, elo, wins, matches)
                    VALUES (?, ?, ?, ?)
                    """,
                    (name, 1200, 0, 0),
                )

            except mariadb.Error as e:
                print(f"Erro inserindo '{name}': {e}")

        conn.commit()
        print(f"{len(names)} disciplinas inseridas com sucesso.")

    except mariadb.Error as e:
        conn.rollback()
        print(f"Erro no banco de dados: {e}")

    finally:
        conn.close()
