## Legisoft

Sistema web para cadastrar, editar e excluir os seguintes dados:

 - Sessões - As sessões devem ter os seguintes campos: título e data.
 - Vereadores - Os vereadores devem ter os seguintes campos: nome e
   partido.
 - Projetos - Os projetos vão ter os seguintes campos: autor (Um dos
   vereadores), tipo de tramitação (Ordinária, Extraordinário, etc..),
   ano, número.

## Permissões

Todos esses dados cadastrados vão ser separados por usuário, então o usuário "joao" vai
somente ver as coisas do "joao" e o usuário "maria" somente ver as coisas da "maria". O
sistema não precisa ter um gerenciamento de usuários, pode ser cadastrado via banco de
dados.

## Caso de uso

Uma sessão acontece para vereadores votarem os projetos, então para que possamos
registrar isso no sistema temos que conseguir vincular os projetos as sessões e também
vincular os vereadores as sessões, e a partir disso salvar qual foi o voto de cada vereador
para cada um dos projetos que estão vinculadas naquela sessão.

## Requisitos

Poder vincular projetos as sessões.
Poder vincular vereadores às sessões.
Poder salvar qual foi o voto do vereador em cada um dos projetos (somente os que estão
vinculados na sessão).
